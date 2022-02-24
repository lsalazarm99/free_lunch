<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Ingredient extends Model
{
    use HasFactory;

    /** @var array<string, mixed> */
    protected $casts = [
        'amount' => 'int',
    ];

    /**
     * @return HasMany<IngredientPurchase>
     */
    public function ingredientPurchases(): HasMany
    {
        return $this->hasMany(IngredientPurchase::class, 'ingredient_id', 'id');
    }

    /**
     * @return HasMany<OrderIngredient>
     */
    public function orderIngredients(): HasMany
    {
        return $this->hasMany(OrderIngredient::class, 'ingredient_id', 'id');
    }
}
