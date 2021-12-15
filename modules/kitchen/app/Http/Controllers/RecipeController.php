<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class RecipeController extends Controller
{
    public function show(int $recipeId): RecipeResource
    {
        $recipe = Recipe::query()
            ->with('recipeIngredients.ingredient')
            ->findOrFail($recipeId)
        ;

        return RecipeResource::make($recipe);
    }

    public function showAll(): AnonymousResourceCollection
    {
        $recipes = Recipe::query()
            ->with('recipeIngredients.ingredient')
            ->get()
        ;

        return RecipeResource::collection($recipes);
    }
}
