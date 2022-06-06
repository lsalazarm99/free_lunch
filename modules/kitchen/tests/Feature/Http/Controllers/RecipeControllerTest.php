<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\RecipeController
 *
 * @internal
 */
final class RecipeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetARecipe(): void
    {
        $recipe = Recipe::inRandomOrder()->firstOrFail();
        $response = $this->getJson("/recipe/{$recipe->id}");

        $response
            ->assertOk()
            ->assertJson(['id' => $recipe->id], true)
        ;
    }

    public function testGet404WhenFailToGetARecipe(): void
    {
        $this->get('/recipe/10000')->assertNotFound();
    }

    public function testGetAllRecipes(): void
    {
        $response = $this->getJson('/recipe/all');
        $recipes = Recipe::all();

        $response
            ->assertOk()
            ->assertJsonCount($recipes->count())
        ;
    }
}
