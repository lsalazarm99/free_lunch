<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Resources;

use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @covers \App\Http\Resources\RecipeResource
 *
 * @internal
 */
class RecipeResourceTest extends TestCase
{
    use RefreshDatabase;

    public function testFormatTheResource(): void
    {
        $recipe = Recipe::firstOrFail();
        $resource = RecipeResource::make($recipe);

        AssertableJson::fromAssertableJsonString(new AssertableJsonString($resource->toJson()))
            ->whereAll(
                [
                    'id' => $recipe->id,
                    'name' => $recipe->name,
                    'description' => $recipe->description,
                ],
            )
            ->whereType('recipeIngredients', 'array')
        ;
    }
}
