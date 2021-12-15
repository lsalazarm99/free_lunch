<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Recipe extends Model
{
    use HasFactory;

    public function recipeIngredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class, 'recipe_id', 'id');
    }

    public function recipeOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'recipe_id', 'id');
    }
}
