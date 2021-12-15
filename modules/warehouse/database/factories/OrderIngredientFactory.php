<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderIngredient;
use Illuminate\Database\Eloquent\Factories\Factory;

final class OrderIngredientFactory extends Factory
{
    protected $model = OrderIngredient::class;

    public function definition(): array
    {
        return [
            'order_id' => $this->faker->randomElement(Order::pluck('id')->all()),
            'ingredient_id' => $this->faker->randomElement(Ingredient::pluck('id')->all()),
            'ingredient_amount' => $this->faker->numberBetween(1, 5),
        ];
    }
}
