<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Models\OrderIngredient
 *
 * @internal
 */
class OrderIngredientTest extends TestCase
{
    use RefreshDatabase;

    public function testGetItsRelationships(): void
    {
        $orderIngredient = OrderIngredient::firstOrFail();

        $this->assertInstanceOf(Order::class, $orderIngredient->order);
        $this->assertInstanceOf(Ingredient::class, $orderIngredient->ingredient);
    }
}
