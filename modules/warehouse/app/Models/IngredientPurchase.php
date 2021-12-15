<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class IngredientPurchase extends Model
{
    use HasFactory;

    protected $casts = [
        'purchased_amount' => 'int',
    ];

    public function ingredient(): HasOne
    {
        return $this->hasOne(Ingredient::class, 'id', 'ingredient_id');
    }

    public function wasSuccessful(): bool
    {
        return $this->purchased_amount !== 0;
    }
}
