<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Order extends Model
{
    use HasFactory;

    /** @var array<string, mixed> */
    protected $casts = [
        'is_in_process' => 'boolean',
        'is_completed' => 'boolean',
        'is_cancelled' => 'boolean',
    ];

    /**
     * @return BelongsTo<Recipe, self>
     */
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }

    public function setAsInProcess(): self
    {
        $this->is_in_process = true;
        $this->is_completed = false;
        $this->is_cancelled = false;

        return $this;
    }

    public function setAsCompleted(): self
    {
        $this->is_in_process = false;
        $this->is_completed = true;
        $this->is_cancelled = false;

        return $this;
    }

    public function setAsCancelled(): self
    {
        $this->is_in_process = false;
        $this->is_completed = false;
        $this->is_cancelled = true;

        return $this;
    }
}
