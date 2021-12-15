<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Order;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Models\Recipe
 *
 * @internal
 */
class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function testGetItsRelationships(): void
    {
        $recipe = Recipe::query()
            ->has('recipeOrders')
            ->firstOrFail()
        ;

        $this->assertInstanceOf(RecipeIngredient::class, $recipe->recipeIngredients->first());
        $this->assertInstanceOf(Order::class, $recipe->recipeOrders->first());
    }
}
