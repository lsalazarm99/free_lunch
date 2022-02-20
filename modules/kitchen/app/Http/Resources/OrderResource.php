<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
final class OrderResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $this->loadMissing('recipe.recipeIngredients.ingredient');

        return [
            'id' => $this->id,
            'is_in_process' => $this->is_in_process,
            'is_completed' => $this->is_completed,
            'is_cancelled' => $this->is_cancelled,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'recipe' => RecipeResource::make($this->recipe),
        ];
    }
}
