<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\KitchenService\KitchenService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

final class KitchenServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        App::singleton(
            KitchenService::class,
            static fn () => new KitchenService(
                config('services.kitchen.domain'),
                config('services.kitchen.protocol'),
            ),
        );
    }
}
