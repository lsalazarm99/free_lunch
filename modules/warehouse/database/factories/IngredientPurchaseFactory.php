<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use Illuminate\Database\Eloquent\Factories\Factory;

final class IngredientPurchaseFactory extends Factory
{
    protected $model = IngredientPurchase::class;

    public function definition(): array
    {
        return [
            'ingredient_id' => $this->faker->randomElement(Ingredient::pluck('id')->all()),
            'purchased_amount' => $this->faker->numberBetween(0, 10),
        ];
    }
}
