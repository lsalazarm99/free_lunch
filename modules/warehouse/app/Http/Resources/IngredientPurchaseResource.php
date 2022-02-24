<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\IngredientPurchase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin IngredientPurchase */
final class IngredientPurchaseResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'purchased_amount' => $this->purchased_amount,
            'was_successful' => $this->wasSuccessful(),
            'created_at' => $this->created_at,

            'ingredient' => IngredientResource::make($this->ingredient),
        ];
    }
}
