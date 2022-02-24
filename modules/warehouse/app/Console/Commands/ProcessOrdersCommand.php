<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderIngredient;
use App\Services\KitchenService\KitchenService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class ProcessOrdersCommand extends Command
{
    protected $signature = 'warehouse:process-orders';
    protected $description = 'Process the undelivered orders and deliver them if possible';

    /** @var EloquentCollection<int, Ingredient> */
    private EloquentCollection $ingredients;

    /** @var EloquentCollection<int, Ingredient> */
    private EloquentCollection $reservedIngredients;

    public function __construct()
    {
        parent::__construct();

        $this->ingredients = EloquentCollection::empty();
        $this->reservedIngredients = EloquentCollection::empty();
    }

    public function handle(KitchenService $kitchenService): int
    {
        $orders = Order::query()
            ->with('orderIngredients.ingredient')
            ->where('is_delivered', '=', false)
            ->whereHas(
                'orderIngredients.ingredient',
                fn (Builder $query) => $query->where('amount', '!=', 0)
            )
            ->orderBy('created_at')
            ->get()
        ;

        if ($orders->count() === 0) {
            $this->info('There are no orders that can be processed right now');

            return 0;
        }

        $this->info("Amount of orders that are going to be processed: {$orders->count()}");

        $this->setIngredientsFromOrders($orders);

        $orders->each(function (Order $order) use ($kitchenService): void {
            if (!$this->thereAreAvailableIngredients()) {
                return;
            }

            $this->newLine();
            $this->info("The following order is being processed: {$order->id}");

            $allRequiredIngredientsAreAvailable = $order->orderIngredients->every(
                function (OrderIngredient $orderIngredient): bool {
                    // If the requested ingredient is already reserved, then it's not available for this order.
                    if ($this->ingredientIsReserved($orderIngredient->ingredient_id)) {
                        $this->warn(
                            'The following ingredient was requested, but is already reserved: '
                            . "{$orderIngredient->ingredient?->name} ({$orderIngredient->ingredient?->id})",
                        );

                        return false;
                    }

                    $availableAmount = $this->getIngredient($orderIngredient->ingredient_id)?->amount ?? 0;
                    $requiredAmount = $orderIngredient->ingredient_amount;

                    return $availableAmount >= $requiredAmount;
                },
            );

            // If not all the required ingredients are available, then we store all the required ingredients as
            // reserved.
            if (!$allRequiredIngredientsAreAvailable) {
                $this->warn('Not all the required ingredients are available for the current order');

                $order->orderIngredients->each(function (OrderIngredient $orderIngredient): void {
                    if ($this->reserveIngredient($orderIngredient->ingredient_id)) {
                        $this->info(
                            'The following ingredient was reserved: '
                            . "{$orderIngredient->ingredient?->name} ({$orderIngredient->ingredient?->id})",
                        );
                    }
                });

                return;
            }

            $this->info('All the required ingredients are available for the current order');

            try {
                DB::transaction(function () use ($kitchenService, $order): void {
                    $this->newLine();

                    $order->orderIngredients->each(function (OrderIngredient $orderIngredient): void {
                        $this->info(
                            'Updating the available amount of the following ingredient: '
                            . "{$orderIngredient->ingredient?->name} ({$orderIngredient->ingredient?->id})",
                        );

                        $ingredient = $this->getIngredient($orderIngredient->ingredient_id);

                        if ($ingredient === null) {
                            $this->warn('The ingredient could not be found.');

                            throw new RuntimeException('An ingredient could not be found');
                        }

                        $ingredient->amount -= $orderIngredient->ingredient_amount;

                        if (!$ingredient->save()) {
                            $this->warn('The ingredient could no be updated');

                            throw new RuntimeException('The ingredient could not be updated');
                        }
                    });

                    $this->info('Updating the status of the order');

                    // Update the order.
                    if (!$order->setAsDelivered()->save()) {
                        $this->warn('The order could no be updated');

                        throw new RuntimeException('The order could not be updated');
                    }

                    $this->info('Delivering the order');

                    try {
                        $kitchenService->deliverIngredients($order);
                    } catch (RequestException $exception) {
                        $this->warn('The order could no be delivered');

                        throw $exception;
                    }
                });
            } catch (RuntimeException|RequestException) {
                $this->warn('The changes were reverted');
            }

            if ($order->refresh()->is_delivered) {
                $this->info('The order was delivered and the ingredients were consumed');
            }

            $this->ingredients->fresh();
        });

        return 0;
    }

    private function getIngredient(int $ingredientId): ?Ingredient
    {
        return $this->ingredients->first(fn (Ingredient $ingredient) => $ingredient->id === $ingredientId);
    }

    /**
     * @param Collection<int, Order> $orders
     */
    private function setIngredientsFromOrders(Collection $orders): void
    {
        /** @var Collection<int, OrderIngredient> $orderIngredients */
        $orderIngredients = $orders
            ->map((static fn (Order $order): Collection => $order->orderIngredients))
            ->flatten(1)
        ;

        /** @var Collection<int, Ingredient> $ingredients */
        $ingredients = $orderIngredients
            ->map(fn (OrderIngredient $orderIngredient): ?Ingredient => $orderIngredient->ingredient)
            ->reject(fn (?Ingredient $ingredient): bool => $ingredient === null)
        ;

        // When the ingredients are purchased, the same ingredient will be purchased only once.
        $ingredients->unique(fn (Ingredient $ingredient): int => $ingredient->id);

        $this->ingredients = EloquentCollection::empty();

        $ingredients->each(function (Ingredient $ingredient): void {
            $this->ingredients->push($ingredient);
        });
    }

    private function ingredientIsReserved(int $ingredientId): bool
    {
        return $this->reservedIngredients->contains(
            fn (Ingredient $reservedIngredient): bool => $reservedIngredient->id === $ingredientId,
        );
    }

    /**
     * @return bool Indicates if the ingredient was reserved or not. It won't reserve the ingredient if it's already
     *              reserved.
     */
    private function reserveIngredient(int $ingredientId): bool
    {
        if ($this->ingredientIsReserved($ingredientId)) {
            return false;
        }

        $ingredient = $this->getIngredient($ingredientId);

        if ($ingredient === null) {
            return false;
        }

        $this->reservedIngredients->push($ingredient);

        return true;
    }

    /**
     * Check if there are available ingredients. An available ingredient is one whose quantity value is greater than
     * zero and it's not reserved.
     */
    private function thereAreAvailableIngredients(): bool
    {
        return $this->ingredients
            ->reject(fn (Ingredient $ingredient) => $this->ingredientIsReserved($ingredient->id))
            ->some(fn (Ingredient $ingredient): bool => $ingredient->amount > 0)
        ;
    }
}
