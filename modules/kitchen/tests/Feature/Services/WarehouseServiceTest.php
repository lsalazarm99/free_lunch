<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Order;
use App\Models\RecipeIngredient;
use App\Services\WarehouseService\WarehouseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

/**
 * @covers \App\Providers\WarehouseServiceProvider
 * @covers \App\Services\WarehouseService\WarehouseService
 *
 * @internal
 */
final class WarehouseServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @doesNotPerformAssertions
     */
    public function testRequestIngredients(): void
    {
        Http::fake();
        $order = Order::where('is_in_process', '=', true)->firstOrFail();

        app()->make(WarehouseService::class)->requestIngredients($order);
    }

    public function testRequestIngredientsFails(): void
    {
        Http::fake(static fn () => Http::response(null, 400));
        $order = Order::where('is_in_process', '=', true)->firstOrFail();

        $this->expectException(RequestException::class);
        app()->make(WarehouseService::class)->requestIngredients($order);
    }

    public function testRequestIngredientsFailsBecauseRecipeDoesNotHaveIngredients(): void
    {
        Http::fake();
        $order = Order::where('is_in_process', '=', true)->firstOrFail();
        $order->recipe?->recipeIngredients->each(function (RecipeIngredient $recipeIngredient) {
            $recipeIngredient->delete();
        });
        $order->refresh();

        $this->expectException(RuntimeException::class);
        app()->make(WarehouseService::class)->requestIngredients($order);
    }
}
