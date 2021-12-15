<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderIngredient;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class OrderController extends Controller
{
    public function storeOrder(Request $request): OrderResource
    {
        $request->validate(
            [
                'order_id' => ['required', 'int'],
                'ingredients' => ['required', 'array', 'min:1'],
                'ingredients.*.id' => ['required', 'int'],
                'ingredients.*.amount' => ['required', 'min:1'],
            ],
        );

        // Check that the order doesn't already exist.

        if (Order::find($request->input('order_id')) !== null) {
            throw new ConflictHttpException('The order already exists.');
        }

        // Check that all the indicated ingredients exist.

        $ingredients = Collection::make($request->input('ingredients'));

        $ingredientsIDs = $ingredients->map(fn (array $ingredient): int => $ingredient['id'])->unique();

        if (Ingredient::whereIn('id', $ingredientsIDs)->count() !== $ingredientsIDs->count()) {
            throw new ModelNotFoundException('Some ingredients could not be found.');
        }

        // Generate the order and its ingredients.

        $order = new Order();
        $order->id = $request->input('order_id');

        $orderIngredients = $ingredients->map(function (array $ingredient) use ($order): OrderIngredient {
            $orderIngredient = new OrderIngredient();
            $orderIngredient->ingredient_id = $ingredient['id'];
            $orderIngredient->ingredient_amount = $ingredient['amount'];
            $orderIngredient->order_id = $order->id;

            return $orderIngredient;
        });

        // Save the order and its ingredients inside a transaction so it can be rolled back if it fails.

        DB::transaction(static function () use ($order, $orderIngredients): void {
            $order->saveOrFail();

            $orderIngredients->each(function (OrderIngredient $orderIngredient): void {
                $orderIngredient->saveOrFail();
            });
        });

        $order->refresh()->load('orderIngredients.ingredient');

        return OrderResource::make($order);
    }
}
