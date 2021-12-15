<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\RecipeIngredientResource;
use App\Models\RecipeIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @covers \App\Http\Resources\RecipeIngredientResource
 *
 * @internal
 */
class RecipeIngredientResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testFormatTheResource(): void
    {
        $recipeIngredient = RecipeIngredient::firstOrFail();
        $resource = RecipeIngredientResource::make($recipeIngredient);

        AssertableJson::fromAssertableJsonString(new AssertableJsonString($resource->toJson()))
            ->whereAll(
                [
                    'id' => $recipeIngredient->id,
                    'ingredients_amount' => $recipeIngredient->ingredients_amount,
                ],
            )
            ->whereType('ingredient', 'array')
            ->missing('recipe')
        ;

        $recipeIngredient->load('recipe');
        $resource = RecipeIngredientResource::make($recipeIngredient);

        AssertableJson::fromAssertableJsonString(new AssertableJsonString($resource->toJson()))
            ->has('recipe')
            ->whereType('recipe', 'array')
        ;
    }
}
