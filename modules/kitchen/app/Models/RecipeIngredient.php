<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class RecipeIngredient extends Model
{
    use HasFactory;

    protected $casts = [
        'ingredients_amount' => 'int',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }

    public function ingredient(): HasOne
    {
        return $this->hasOne(Ingredient::class, 'id', 'ingredient_id');
    }
}
