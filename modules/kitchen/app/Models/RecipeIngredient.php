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

    /** @var array<string, mixed> */
    protected $casts = [
        'ingredients_amount' => 'int',
    ];

    /**
     * @return BelongsTo<Recipe, self>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }

    /**
     * @return HasOne<Ingredient>
     */
    public function ingredient(): HasOne
    {
        return $this->hasOne(Ingredient::class, 'id', 'ingredient_id');
    }
}
