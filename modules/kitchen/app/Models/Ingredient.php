<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Ingredient extends Model
{
    use HasFactory;

    public function recipeIngredient(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class, 'ingredient_id', 'id');
    }
}
