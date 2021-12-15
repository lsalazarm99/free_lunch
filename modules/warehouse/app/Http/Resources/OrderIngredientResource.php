<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\OrderIngredient;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrderIngredient */
final class OrderIngredientResource extends JsonResource
{
    /**
     * @param Request $request
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'ingredient_amount' => $this->ingredient_amount,

            'order' => new OrderResource($this->whenLoaded('order')),
            'ingredient' => new IngredientResource($this->ingredient),
        ];
    }
}
