<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Database\Eloquent\Factories\Factory;

final class RecipeIngredientFactory extends Factory
{
    protected $model = RecipeIngredient::class;

    public function definition(): array
    {
        return [
            'recipe_id' => $this->faker->randomElement(Recipe::pluck('id')->all()),
            'ingredient_id' => $this->faker->randomElement(Ingredient::pluck('id')->all()),
            'ingredients_amount' => $this->faker->numberBetween(1, 5),
        ];
    }
}
