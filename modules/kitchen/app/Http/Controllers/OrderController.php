<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Recipe;
use App\Services\WarehouseService\WarehouseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

final class OrderController extends Controller
{
    public function show(int $orderId): OrderResource
    {
        $order = Order::query()
            ->with('recipe.recipeIngredients.ingredient')
            ->findOrFail($orderId)
        ;

        return OrderResource::make($order);
    }

    /**
     * @return AnonymousResourceCollection<OrderResource>
     */
    public function search(Request $request): AnonymousResourceCollection
    {
        $request->validate(
            [
                'in_process' => ['nullable', 'bool'],
                'completed' => ['nullable', 'bool'],
                'cancelled' => ['nullable', 'bool'],
                'recipe_id' => ['nullable', Rule::exists(Recipe::class, 'id')],
                'max_items_number' => ['nullable', 'int', 'between:1,15'],
            ],
        );

        $ordersQuery = Order::query()
            ->with('recipe.recipeIngredients.ingredient')
            ->orderBy('created_at', 'desc')
            ->where(function (Builder $query) use ($request) {
                if ($request->filled('in_process')) {
                    $query->orWhere('is_in_process', '=', $request->boolean('in_process'));
                }

                if ($request->filled('completed')) {
                    $query->orWhere('is_completed', '=', $request->boolean('completed'));
                }

                if ($request->filled('cancelled')) {
                    $query->orWhere('is_cancelled', '=', $request->boolean('cancelled'));
                }
            })
        ;

        if ($request->filled('recipe_id')) {
            $ordersQuery->where('recipe_id', '=', $request->input('recipe_id'));
        }

        if ($request->filled('max_items_number')) {
            $orders = $ordersQuery->paginate($request->input('max_items_number'));
        } else {
            $orders = $ordersQuery->paginate(15);
        }

        $orders->withQueryString();

        return OrderResource::collection($orders);
    }

    public function createRandom(WarehouseService $warehouseService): OrderResource
    {
        $recipe = Recipe::query()
            ->with('recipeIngredients.ingredient')
            ->has('recipeIngredients.ingredient')
            ->inRandomOrder()
            ->firstOrFail()
        ;

        $order = new Order();
        $order->recipe()->associate($recipe);
        $order->setAsInProcess();

        try {
            $order->saveOrFail();
        } catch (Throwable $e) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'The order could not be generated.',
                $e->getPrevious(),
            );
        }

        try {
            $warehouseService->requestIngredients($order);
        } catch (RuntimeException|ModelNotFoundException $e) {
            $order->setAsCancelled();

            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                "An attempt was made to generate the order with the recipe \"{$recipe->name}\" with ID {$recipe->id}"
                . " but it seems that the recipe is incomplete or one of its ingredients doesn't exist.",
                $e->getPrevious(),
            );
        } catch (RequestException $e) {
            $order->setAsCancelled();

            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'The order could not be generated.',
                $e->getPrevious(),
            );
        }

        return OrderResource::make($order);
    }

    public function deliverIngredients(int $orderId, ResponseFactory $response): Response
    {
        $order = Order::query()->findOrFail($orderId);

        if (!$order->is_in_process) {
            throw new HttpException(
                Response::HTTP_CONFLICT,
                'The order is not in process.',
            );
        }

        // Theoretically, since we have all the ingredients, we should simply wait for the chefs to confirm that the
        // order has been completed. But let's just imagine that, as soon as the ingredients are delivered, the food
        // is already prepared because our chefs were trained under supernatural conditions so they have the ability
        // to cook instantly.

        $order->setAsCompleted();

        try {
            $order->saveOrFail();
        } catch (Throwable $exception) {
            throw new HttpException(
                Response::HTTP_INTERNAL_SERVER_ERROR,
                'The order could not be updated.',
                $exception->getPrevious(),
            );
        }

        return $response->noContent();
    }
}
