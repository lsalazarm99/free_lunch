<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Ingredient;
use App\Models\RecipeIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Models\Ingredient
 *
 * @internal
 */
class IngredientTest extends TestCase
{
    use RefreshDatabase;

    public function testGetItsRelationships(): void
    {
        $ingredient = Ingredient::query()
            ->has('recipeIngredient')
            ->firstOrFail()
        ;

        $this->assertInstanceOf(RecipeIngredient::class, $ingredient->recipeIngredient->first());
    }
}
