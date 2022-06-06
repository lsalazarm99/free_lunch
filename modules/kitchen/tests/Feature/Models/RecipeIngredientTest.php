<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Models\RecipeIngredient
 *
 * @internal
 */
final class RecipeIngredientTest extends TestCase
{
    use RefreshDatabase;

    public function testGetItsRelationships(): void
    {
        $recipeIngredient = RecipeIngredient::firstOrFail();

        $this->assertInstanceOf(Recipe::class, $recipeIngredient->recipe);
        $this->assertInstanceOf(Ingredient::class, $recipeIngredient->ingredient);
    }
}
