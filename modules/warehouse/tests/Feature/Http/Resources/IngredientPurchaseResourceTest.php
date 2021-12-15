<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\IngredientPurchaseResource;
use App\Models\IngredientPurchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @covers \App\Http\Resources\IngredientPurchaseResource
 *
 * @internal
 */
class IngredientPurchaseResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testFormatTheResource(): void
    {
        $ingredientPurchase = IngredientPurchase::firstOrFail();
        $resource = IngredientPurchaseResource::make($ingredientPurchase);

        AssertableJson::fromAssertableJsonString(new AssertableJsonString($resource->toJson()))
            ->whereAll(
                [
                    'id' => $ingredientPurchase->id,
                    'purchased_amount' => $ingredientPurchase->purchased_amount,
                    'was_successful' => $ingredientPurchase->wasSuccessful(),
                    'created_at' => $ingredientPurchase->created_at?->toJSON(),
                ],
            )
            ->whereType('ingredient', 'array')
        ;
    }
}
