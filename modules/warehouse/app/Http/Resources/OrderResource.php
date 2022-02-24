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
        $this->loadMissing('orderIngredients.ingredient');

        return [
            'id' => $this->id,
            'is_delivered' => $this->is_delivered,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'order_ingredients' => OrderIngredientResource::collection($this->orderIngredients),
        ];
    }
}
