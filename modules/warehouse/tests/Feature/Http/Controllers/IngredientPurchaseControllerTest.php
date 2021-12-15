<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\IngredientPurchaseController
 *
 * @internal
 */
class IngredientPurchaseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetTheCorrectAmountOfItemsWhenSearchIngredientPurchases(): void
    {
        $this->get('/ingredient_purchase/search')
            ->assertOk()
            ->assertJsonCount(15, 'data')
        ;

        $this->get('/ingredient_purchase/search?max_items_number=1')
            ->assertOk()
            ->assertJsonCount(1, 'data')
        ;
    }

    public function testGet422WhenSearchIngredientPurchasesAndTheAmountOfItemsIsOutsideTheLimits(): void
    {
        $this->get('/ingredient_purchase/search?max_items_number=16')
            ->assertStatus(422)
        ;

        $this->get('/ingredient_purchase/search?max_items_number=0')
            ->assertStatus(422)
        ;
    }

    public function testGet422WhenSearchIngredientPurchasesWithAnIngredientThatDoesNotExist(): void
    {
        $this->get('/ingredient_purchase/search?ingredient_id=10000')
            ->assertStatus(422)
        ;
    }
}
