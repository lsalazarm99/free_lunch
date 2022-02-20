<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Services\WarehouseService\WarehouseService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use RuntimeException;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\OrderController
 *
 * @internal
 */
class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAOrder(): void
    {
        $order = Order::inRandomOrder()->firstOrFail();
        $response = $this->get("/order/{$order->id}");

        $response
            ->assertOk()
            ->assertJson(['id' => $order->id], true)
        ;
    }

    public function testGet404WhenFailToGetAOrder(): void
    {
        $this->get('/order/10000')->assertNotFound();
    }

    public function testSearchOrders(): void
    {
        $this->get('/order/search?in_process=1')
            ->assertJsonPath('meta.total', Order::where('is_in_process', '=', true)->count())
        ;

        $this->get('/order/search?cancelled=1')
            ->assertJsonPath('meta.total', Order::where('is_cancelled', '=', true)->count())
        ;

        $this->get('/order/search?in_process=1&completed=1')
            ->assertJsonPath(
                'meta.total',
                Order::query()
                    ->orWhere('is_in_process', '=', true)
                    ->orWhere('is_completed', '=', true)
                    ->count(),
            )
        ;

        $this->get('/order/search?recipe_id=1')
            ->assertJsonPath('meta.total', Order::where('recipe_id', '=', 1)->count())
        ;

        $this->get('/order/search?recipe_id=1&in_process=1')
            ->assertJsonPath(
                'meta.total',
                Order::query()
                    ->where('recipe_id', '=', 1)
                    ->where('is_in_process', '=', true)
                    ->count(),
            )
        ;
    }

    public function testGetTheCorrectAmountOfItemsWhenSearchOrders(): void
    {
        $this->get('/order/search')
            ->assertOk()
            ->assertJsonCount(15, 'data')
        ;

        $this->get('/order/search?max_items_number=1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
        ;
    }

    public function testGet422WhenSearchOrdersAndTheAmountOfItemsIsOutsideTheLimits(): void
    {
        $this->get('/order/search?max_items_number=16')
            ->assertStatus(422)
        ;

        $this->get('/order/search?max_items_number=0')
            ->assertStatus(422)
        ;
    }

    public function testGet422WhenSearchResourcesWithARecipeThatDoesNotExist(): void
    {
        $this->get('/order/search?recipe_id=10000')
            ->assertStatus(422)
        ;
    }

    public function testCreateRandomOrder(): void
    {
        $this->mock(
            WarehouseService::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('requestIngredients')->once();
            },
        );

        $response = $this->post('/order/random');

        $response->assertStatus(201);
        $this->assertNotNull($response->json('id'));
    }

    public function testCreateRandomOrderFailsBecauseOfDatabaseIssues(): void
    {
        $this->mock(
            WarehouseService::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('requestIngredients')
                    ->andThrow(RuntimeException::class)
                ;
            },
        );

        $response = $this->post('/order/random');

        $response->assertStatus(500);

        $this->mock(
            WarehouseService::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('requestIngredients')
                    ->andThrow(ModelNotFoundException::class)
                ;
            },
        );

        $response = $this->post('/order/random');

        $response->assertStatus(500);
    }

    public function testCreateRandomOrderFailsBecauseOfNetworkIssues(): void
    {
        Http::fake(static fn () => Http::response(null, 400));

        $this->post('/order/random')
            ->assertStatus(500)
        ;
    }

    public function testIngredientsAreDelivered(): void
    {
        $order = Order::where('is_in_process', '=', true)->firstOrFail();
        $response = $this->putJson("/order/deliver_ingredients/{$order->id}");

        $response->assertNoContent();
    }

    public function testOrderIsCompletedWhenIngredientsAreDelivered(): void
    {
        $order = Order::where('is_in_process', '=', true)->firstOrFail();
        $this->putJson("/order/deliver_ingredients/{$order->id}");

        $this->assertNotSame($order->id, Order::where('is_in_process', '=', true)->firstOrFail()->id);
    }

    public function testGet409IfTheIngredientsAreDeliveredButTheOrderIsNotInProcess(): void
    {
        $order = Order::where('is_in_process', '=', true)->firstOrFail()->setAsCompleted();
        $order->save();

        $this->putJson("/order/deliver_ingredients/{$order->id}")
            ->assertStatus(409)
        ;
    }
}
