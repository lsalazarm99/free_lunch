<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
final class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [];
    }

    /**
     * @return Factory<Order>
     */
    public function isDelivered(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_delivered' => true,
        ]);
    }

    /**
     * @return Factory<Order>
     */
    public function isNotDelivered(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_delivered' => false,
        ]);
    }
}
