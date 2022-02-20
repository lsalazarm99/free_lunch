<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\WarehouseService\WarehouseService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

final class WarehouseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        App::singleton(
            WarehouseService::class,
            static fn () => new WarehouseService(
                Config::get('services.warehouse.domain'),
                Config::get('services.warehouse.protocol'),
            ),
        );
    }
}
