<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\OrderIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\OrderController
 *
 * @internal
 */
final class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateOrder(): void
    {
        $response = $this->post('/order', [
            'order_id' => 10000,
            'ingredients' => [
                [
                    'id' => 1,
                    'amount' => 5,
                ],
                [
                    'id' => 2,
                    'amount' => 4,
                ],
                [
                    'id' => 3,
                    'amount' => 3,
                ],
            ],
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('id', 10000);

        $order = Order::findOrFail(10000);
        $order->load('orderIngredients.ingredient');

        $this->assertSame(3, $order->orderIngredients->count());
        $this->assertFalse($order->is_delivered);
        $this->assertSame(
            4,
            $order->orderIngredients->first(
                fn (OrderIngredient $orderIngredient): bool => $orderIngredient->ingredient?->id === 2,
            )?->ingredient_amount,
        );
    }

    public function testCreateOrderFailsBecauseOfMissingParameters(): void
    {
        $this->post('/order')->assertStatus(422);
        $this->post('/order', ['order_id' => 10000])->assertStatus(422);
        $this->post('/order', ['order_id' => 10000, 'ingredients' => []])->assertStatus(422);
        $this->post('/order', [
            'ingredients' => [
                [
                    'id' => 1,
                    'amount' => 1,
                ],
            ],
        ])
            ->assertStatus(422)
        ;
    }

    public function testCreateOrderFailsBecauseTheOrderDoesAlreadyExist(): void
    {
        $this->post('/order', [
            'order_id' => 1,
            'ingredients' => [
                [
                    'id' => 1,
                    'amount' => 1,
                ],
            ],
        ])
            ->assertStatus(409)
        ;
    }

    public function testCreateOrderFailsBecauseSomeIngredientsDoNotExist(): void
    {
        $this->post('/order', [
            'order_id' => 10000,
            'ingredients' => [
                [
                    'id' => 10000,
                    'amount' => 1,
                ],
            ],
        ])
            ->assertNotFound()
        ;
    }
}
