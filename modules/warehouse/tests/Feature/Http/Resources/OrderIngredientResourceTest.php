<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\OrderIngredientResource;
use App\Models\OrderIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @covers \App\Http\Resources\OrderIngredientResource
 *
 * @internal
 */
final class OrderIngredientResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testFormatTheResource(): void
    {
        $orderIngredient = OrderIngredient::firstOrFail();
        $resource = OrderIngredientResource::make($orderIngredient);

        AssertableJson::fromAssertableJsonString(new AssertableJsonString($resource->toJson()))
            ->whereAll(
                [
                    'id' => $orderIngredient->id,
                    'ingredient_amount' => $orderIngredient->ingredient_amount,
                ],
            )
            ->whereType('ingredient', 'array')
            ->missing('order')
        ;

        $orderIngredient->load('order');
        $resource = OrderIngredientResource::make($orderIngredient);

        AssertableJson::fromAssertableJsonString(new AssertableJsonString($resource->toJson()))
            ->has('order')
            ->whereType('order', 'array')
        ;
    }
}
