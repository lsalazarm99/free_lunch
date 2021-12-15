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
class IngredientPurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function testGetItsRelationships(): void
    {
        $ingredientPurchase = IngredientPurchase::firstOrFail();

        $this->assertInstanceOf(Ingredient::class, $ingredientPurchase->ingredient);
    }

    public function testCheckIfWasSuccessful(): void
    {
        $ingredientPurchase = IngredientPurchase::query()
            ->where('purchased_amount', '!=', 0)
            ->firstOrFail()
        ;

        $this->assertTrue($ingredientPurchase->wasSuccessful());

        $ingredientPurchase = IngredientPurchase::query()
            ->where('purchased_amount', '=', 0)
            ->firstOrFail()
        ;

        $this->assertFalse($ingredientPurchase->wasSuccessful());
    }
}
