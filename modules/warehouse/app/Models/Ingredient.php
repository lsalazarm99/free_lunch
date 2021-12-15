<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Ingredient extends Model
{
    use HasFactory;

    protected $casts = [
        'amount' => 'int',
    ];

    public function ingredientPurchases(): HasMany
    {
        return $this->hasMany(IngredientPurchase::class, 'ingredient_id', 'id');
    }

    public function orderIngredients(): HasMany
    {
        return $this->hasMany(OrderIngredient::class, 'ingredient_id', 'id');
    }
}
