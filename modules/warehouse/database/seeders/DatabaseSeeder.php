<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use App\Models\Order;
use App\Models\OrderIngredient;
use Database\Factories\OrderFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Ingredient::factory()->count(12)->create();

        IngredientPurchase::factory()->count(50)->create();

        /** @var OrderFactory $orderFactory */
        $orderFactory = Order::factory()->has(OrderIngredient::factory()->count(3));

        $orderFactory->count(30)->isDelivered()->create();
        $orderFactory->count(5)->isNotDelivered()->create();
    }
}
