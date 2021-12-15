<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Order;
use App\Models\Recipe;
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

        $this->assertInstanceOf(Recipe::class, $order->recipe);
    }

    public function testSetTheStatusAsInProcess(): void
    {
        $order = Order::firstOrFail();

        $order->setAsInProcess();

        $this->assertTrue($order->is_in_process);
        $this->assertFalse($order->is_completed);
        $this->assertFalse($order->is_cancelled);
    }

    public function testSetTheStatusAsCompleted(): void
    {
        $order = Order::firstOrFail();

        $order->setAsCompleted();

        $this->assertFalse($order->is_in_process);
        $this->assertTrue($order->is_completed);
        $this->assertFalse($order->is_cancelled);
    }

    public function testSetTheStatusAsCancelled(): void
    {
        $order = Order::firstOrFail();

        $order->setAsCancelled();

        $this->assertFalse($order->is_in_process);
        $this->assertFalse($order->is_completed);
        $this->assertTrue($order->is_cancelled);
    }
}
