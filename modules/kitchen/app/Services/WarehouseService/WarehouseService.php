<?php

declare(strict_types=1);

namespace App\Services\WarehouseService;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WarehouseService
{
    private PendingRequest $client;

    public function __construct(string $domain, string $protocol = 'http')
    {
        $this->client = Http::baseUrl("{$protocol}://{$domain}");
    }

    /**
     * @throws RuntimeException
     * @throws ModelNotFoundException
     * @throws RequestException
     */
    public function requestIngredients(Order $order): void
    {
        // Before sending the request, we need to verify that the recipe of the order and its ingredients exists.

        if ($order->recipe === null) {
            throw (new ModelNotFoundException('The recipe of the order was not found.'))
                ->setModel(Recipe::class, $order->recipe_id)
            ;
        }

        if ($order->recipe->recipeIngredients->isEmpty()) {
            throw new RuntimeException('The recipe of the order has no ingredients registered.');
        }

        $nonexistentRecipeIngredients = $order->recipe->recipeIngredients
            ->filter(fn (RecipeIngredient $recipeIngredient) => $recipeIngredient->ingredient === null)
        ;

        if ($nonexistentRecipeIngredients->isNotEmpty()) {
            throw (new ModelNotFoundException('Some ingredients of the recipe of the order were not found.'))
                ->setModel(
                    Ingredient::class,
                    $nonexistentRecipeIngredients->map(
                        fn (RecipeIngredient $recipeIngredient) => $recipeIngredient->ingredient_id,
                    )->all(),
                )
            ;
        }

        // At this point, we know that the order has all the data necessary to create the request.

        $this->client->post('', [
            'order_id' => $order->id,
            'ingredients' => $order->recipe->recipeIngredients
                ->mapWithKeys(fn (RecipeIngredient $recipeIngredient) => [
                    $recipeIngredient->ingredient?->code => $recipeIngredient->ingredients_amount,
                ]),
        ])
            ->throw()
        ;
    }
}
