<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Models\IngredientPurchase
 *
 * @internal
 */
final class IngredientPurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function testGetItsRelationships(): void
    {
        $ingredientPurchase = IngredientPurchase::firstOrFail();

        $this->assertInstanceOf(Ingredient::class, $ingredientPurchase->ingredient);
    }

    public function testCheckIfWasSuccessful(): void
    {
        $ingredientPurchase = IngredientPurchase::firstOrFail();

        $ingredientPurchase->purchased_amount = 1;
        $this->assertTrue($ingredientPurchase->wasSuccessful());

        $ingredientPurchase->purchased_amount = 0;
        $this->assertFalse($ingredientPurchase->wasSuccessful());
    }
}
