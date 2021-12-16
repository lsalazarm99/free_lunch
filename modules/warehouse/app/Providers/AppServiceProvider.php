<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        URL::forceScheme(Str::contains(config('app.url'), 'https://') ? 'https' : null);
        URL::forceRootUrl(config('app.url'));
        AbstractPaginator::currentPathResolver(static fn () => app('url')->current());
        JsonResource::withoutWrapping();
    }
}
