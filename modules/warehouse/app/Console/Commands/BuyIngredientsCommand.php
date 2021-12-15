<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use App\Models\Order;
use App\Models\OrderIngredient;
use App\Services\FoodShopService\FoodShopService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Collection;
use RuntimeException;
use Throwable;

class BuyIngredientsCommand extends Command
{
    protected $signature = 'warehouse:buy-ingredients';
    protected $description = 'Buy required ingredients from the food shop';

    public function handle(FoodShopService $foodShopService): int
    {
        $orders = Order::query()
            ->with('orderIngredients.ingredient')
            ->where('is_delivered', '=', false)
            ->get()
        ;

        if ($orders->count() === 0) {
            $this->info('There are no undelivered orders so there is no need to buy ingredients');

            return 0;
        }

        $this->info("Amount of orders that need to be processed: {$orders->count()}");

        /** @var Collection<OrderIngredient> $orderIngredients */
        $orderIngredients = $orders
            ->map((static fn (Order $order): Collection => $order->orderIngredients))
            ->flatten(1)
        ;

        /** @var Collection<Ingredient> $ingredients */
        $ingredients = $orderIngredients
            ->map(fn (OrderIngredient $orderIngredient): ?Ingredient => $orderIngredient->ingredient)
            ->reject(fn (?Ingredient $ingredient): bool => $ingredient === null)
            // When the ingredients are purchased, the same ingredient will be purchased only once.
            ->unique(fn (Ingredient $ingredient): int => $ingredient->id)
            ->sortBy(fn (Ingredient $ingredient): int => $ingredient->id)
        ;

        $this->info('The following ingredients are going to be bought:');
        $this->table(
            ['id', 'name'],
            $ingredients->map(fn (Ingredient $ingredient): array => [$ingredient->id, $ingredient->name]),
        );

        $ingredients->each(function (Ingredient $ingredient) use ($foodShopService): void {
            $this->newLine();
            $this->info("Buying the following ingredient: {$ingredient->name} ({$ingredient->id})");

            try {
                $amountBought = $foodShopService->buyIngredient($ingredient);
            } catch (RuntimeException|RequestException $exception) {
                $this->warn('The following error was handled: ' . $exception->getMessage());

                return;
            }

            $this->info("The following amount was purchased: {$amountBought}");

            // Register the purchase.

            $ingredientPurchase = new IngredientPurchase();
            $ingredientPurchase->purchased_amount = $amountBought;
            $ingredientPurchase->ingredient_id = $ingredient->id;

            try {
                $ingredientPurchase->saveOrFail();
            } catch (Throwable) {
                $this->warn(
                    'The purchase of the following ingredient could not be saved: '
                    . "{$ingredient->name} ({$ingredient->id})",
                );

                return;
            }

            $this->info("The current purchase was saved with the following ID: {$ingredientPurchase->id}");

            // Update the available amount of ingredients.

            $ingredient->amount += $amountBought;

            try {
                $ingredient->saveOrFail();
            } catch (Throwable) {
                $this->warn(
                    'The amount bought of the following ingredient could not be added: '
                    . "{$ingredient->name} ({$ingredient->id})",
                );

                return;
            }

            $this->info('The purchase and its registration were successful');
        });

        return 0;
    }
}
