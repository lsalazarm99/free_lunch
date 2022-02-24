<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\IngredientController
 *
 * @internal
 */
final class IngredientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetAllIngredients(): void
    {
        $this->getJson('/ingredient/all')
            ->assertOk()
            ->assertJsonCount(Ingredient::count())
        ;
    }
}
