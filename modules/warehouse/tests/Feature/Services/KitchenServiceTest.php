<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\Order;
use App\Services\KitchenService\KitchenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @covers \App\Providers\KitchenServiceProvider
 * @covers \App\Services\KitchenService\KitchenService
 *
 * @internal
 */
class KitchenServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @doesNotPerformAssertions
     */
    public function testDeliverIngredients(): void
    {
        Http::fake();
        app()->make(KitchenService::class)->deliverIngredients(Order::inRandomOrder()->firstOrFail());
    }

    public function testDeliverIngredientsFails(): void
    {
        Http::fake(static fn () => Http::response(null, 400));

        $this->expectException(RequestException::class);
        app()->make(KitchenService::class)->deliverIngredients(Order::firstOrFail());
    }
}
