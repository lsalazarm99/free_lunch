<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Order extends Model
{
    use HasFactory;

    /** @var array<string, mixed> */
    protected $casts = [
        'is_delivered' => 'boolean',
    ];

    /**
     * @return HasMany<OrderIngredient>
     */
    public function orderIngredients(): HasMany
    {
        return $this->hasMany(OrderIngredient::class, 'order_id', 'id');
    }

    public function setAsDelivered(): self
    {
        $this->is_delivered = true;

        return $this;
    }
}
