<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use App\Models\OrderIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Models\Ingredient
 *
 * @internal
 */
final class IngredientTest extends TestCase
{
    use RefreshDatabase;

    public function testGetItsRelationships(): void
    {
        $ingredient = Ingredient::query()
            ->has('ingredientPurchases')
            ->firstOrFail()
        ;

        $this->assertInstanceOf(IngredientPurchase::class, $ingredient->ingredientPurchases->first());

        $ingredient = Ingredient::query()
            ->has('orderIngredients')
            ->firstOrFail()
        ;

        $this->assertInstanceOf(OrderIngredient::class, $ingredient->orderIngredients->first());
    }
}
