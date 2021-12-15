<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\IngredientResource;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @covers \App\Http\Resources\IngredientResource
 *
 * @internal
 */
class IngredientResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testFormatTheResource(): void
    {
        $ingredient = Ingredient::firstOrFail();
        $resource = IngredientResource::make($ingredient);

        AssertableJson::fromAssertableJsonString(new AssertableJsonString($resource->toJson()))
            ->whereAll(
                [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                    'code' => $ingredient->code,
                    'amount' => $ingredient->amount,
                ],
            )
        ;
    }
}
