<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\FoodShopService\FoodShopService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

final class FoodShopServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        App::singleton(
            FoodShopService::class,
            static fn () => new FoodShopService(
                config('services.food_shop.domain'),
                config('services.food_shop.protocol'),
            ),
        );
    }
}
