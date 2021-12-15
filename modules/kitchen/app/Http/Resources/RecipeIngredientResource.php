<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\RecipeIngredient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin RecipeIngredient */
final class RecipeIngredientResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,

            'recipe' => RecipeResource::make($this->whenLoaded('recipe')),
            'ingredient' => IngredientResource::make($this->ingredient),
            'ingredients_amount' => $this->ingredients_amount,
        ];
    }
}
