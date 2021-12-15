<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Recipe */
final class RecipeResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        $this->loadMissing('recipeIngredients.ingredient');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,

            'recipe_ingredients' => RecipeIngredientResource::collection($this->recipeIngredients),
        ];
    }
}
