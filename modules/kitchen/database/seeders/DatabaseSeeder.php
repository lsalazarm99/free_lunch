<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Database\Factories\OrderFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Ingredient::factory()->count(12)->create();

        Recipe::factory()
            ->has(RecipeIngredient::factory()->count(4))
            ->count(6)
            ->create()
        ;

        /** @var OrderFactory $orderFactory */
        $orderFactory = Order::factory();

        $orderFactory->count(20)->inProcess()->create();
        $orderFactory->count(10)->completed()->create();
        $orderFactory->count(5)->cancelled()->create();
    }
}
