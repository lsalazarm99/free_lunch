<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Ingredient;
use App\Services\FoodShopService\FoodShopService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Tests\TestCase;

/**
 * @covers \App\Providers\FoodShopServiceProvider
 * @covers \App\Services\FoodShopService\FoodShopService
 *
 * @internal
 */
final class FoodShopServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testBuyIngredient(): void
    {
        Http::fake(static fn () => Http::response(['quantitySold' => 5]));
        $amountBought = app()->make(FoodShopService::class)->buyIngredient(Ingredient::firstOrFail());

        $this->assertSame(5, $amountBought);
    }

    public function testBuyIngredientFailsBecauseDoesNotReceiveAnInteger(): void
    {
        Http::fake(static fn () => Http::response(['quantitySold' => 0.1]));

        $this->expectException(RuntimeException::class);
        app()->make(FoodShopService::class)->buyIngredient(Ingredient::firstOrFail());
    }

    public function testBuyIngredientFails(): void
    {
        Http::fake(static fn () => Http::response(null, 400));

        $this->expectException(RequestException::class);
        app()->make(FoodShopService::class)->buyIngredient(Ingredient::firstOrFail());
    }
}
