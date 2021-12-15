<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Order;
use App\Models\OrderIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Models\Order
 *
 * @internal
 */
class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function testGetItsRelationships(): void
    {
        $order = Order::firstOrFail();

        $this->assertInstanceOf(OrderIngredient::class, $order->orderIngredients->first());
    }

    public function testCheckIfOrderIsSetAsDelivered(): void
    {
        $order = Order::query()
            ->where('is_delivered', '=', false)
            ->firstOrFail()
            ->setAsDelivered()
        ;

        $this->assertTrue($order->is_delivered);
    }
}
