<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Order;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
final class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'recipe_id' => $this->faker->randomElement(Recipe::pluck('id')->all()),
        ];
    }

    /**
     * @return Factory<Order>
     */
    public function inProcess(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_in_process' => true,
            'is_completed' => false,
            'is_cancelled' => false,
        ]);
    }

    /**
     * @return Factory<Order>
     */
    public function completed(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_in_process' => false,
            'is_completed' => true,
            'is_cancelled' => false,
        ]);
    }

    /**
     * @return Factory<Order>
     */
    public function cancelled(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_in_process' => false,
            'is_completed' => false,
            'is_cancelled' => true,
        ]);
    }
}
