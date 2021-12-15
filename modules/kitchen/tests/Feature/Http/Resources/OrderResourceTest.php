<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @covers \App\Http\Resources\OrderResource
 *
 * @internal
 */
class OrderResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testFormatTheResource(): void
    {
        $order = Order::firstOrFail();
        $resource = OrderResource::make($order);

        AssertableJson::fromAssertableJsonString(new AssertableJsonString($resource->toJson()))
            ->whereAll(
                [
                    'id' => $order->id,
                    'is_in_process' => $order->is_in_process,
                    'is_completed' => $order->is_completed,
                    'is_cancelled' => $order->is_cancelled,
                    'created_at' => $order->created_at?->toJSON(),
                    'updated_at' => $order->updated_at?->toJSON(),
                ],
            )
            ->whereType('recipe', 'array')
        ;
    }
}
