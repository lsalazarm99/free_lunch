<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\Console\Commands\ProcessOrdersCommand;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\OrderIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @covers \App\Console\Commands\ProcessOrdersCommand
 *
 * @internal
 */
class ProcessOrdersCommandTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = false;

    public function testProcessOnlyUndeliveredOrders(): void
    {
        Http::fake();
        Ingredient::factory()->count(12)->create();
        Order::factory()
            ->has(OrderIngredient::factory()->count(3))
            ->count(5)
            ->create()
        ;
        Order::factory()
            ->has(OrderIngredient::factory()->count(3))
            ->isDelivered()
            ->count(5)
            ->create()
        ;

        $this->artisan(ProcessOrdersCommand::class)
            ->expectsOutput('Amount of orders that are going to be processed: 5')
        ;
    }

    public function testUpdatesTheOrderWhenDelivered(): void
    {
        Http::fake();
        Ingredient::factory()->state(['amount' => 1000])->count(12)->create();
        Order::factory()
            ->has(OrderIngredient::factory()->count(3))
            ->count(5)
            ->create()
        ;

        $this->artisan(ProcessOrdersCommand::class)->execute();

        $this->assertSame(5, Order::where('is_delivered', '=', true)->count());
    }

    public function testDecreasesTheAmountOfIngredients(): void
    {
        Http::fake();
        Ingredient::factory()->state(['amount' => 1000])->count(12)->create();
        Order::factory()
            ->has(OrderIngredient::factory()->count(3))
            ->count(5)
            ->create()
        ;

        $this->artisan(ProcessOrdersCommand::class)->execute();

        Ingredient::all()->load('orderIngredients')->each(function (Ingredient $ingredient): void {
            $usedAmount = $ingredient->orderIngredients
                ->sum(fn (OrderIngredient $orderIngredient): int => $orderIngredient->ingredient_amount)
            ;

            $this->assertSame(1000 - $usedAmount, $ingredient->amount);
        });
    }

    public function testDoNotUseReservedIngredients(): void
    {
        Http::fake();
        Ingredient::factory()->state(['amount' => 10])->count(4)->create();
        Order::factory()->count(4)->create();
        OrderIngredient::factory()
            ->state(['order_id' => 1, 'ingredient_id' => 1, 'ingredient_amount' => 5])
            ->create()
        ;
        OrderIngredient::factory()
            ->state(['order_id' => 1, 'ingredient_id' => 2, 'ingredient_amount' => 5])
            ->create()
        ;
        OrderIngredient::factory()
            ->state(['order_id' => 2, 'ingredient_id' => 2, 'ingredient_amount' => 7])
            ->create()
        ;
        OrderIngredient::factory()
            ->state(['order_id' => 2, 'ingredient_id' => 3, 'ingredient_amount' => 3])
            ->create()
        ;
        OrderIngredient::factory()
            ->state(['order_id' => 3, 'ingredient_id' => 2, 'ingredient_amount' => 5])
            ->create()
        ;
        OrderIngredient::factory()
            ->state(['order_id' => 3, 'ingredient_id' => 3, 'ingredient_amount' => 5])
            ->create()
        ;
        OrderIngredient::factory()
            ->state(['order_id' => 4, 'ingredient_id' => 1, 'ingredient_amount' => 5])
            ->create()
        ;
        OrderIngredient::factory()
            ->state(['order_id' => 4, 'ingredient_id' => 4, 'ingredient_amount' => 5])
            ->create()
        ;

        $this->artisan(ProcessOrdersCommand::class)->execute();

        $this->assertTrue(Order::findOrFail(1)->is_delivered);
        $this->assertFalse(Order::findOrFail(2)->is_delivered);
        $this->assertFalse(Order::findOrFail(3)->is_delivered);
        $this->assertTrue(Order::findOrFail(4)->is_delivered);
    }

    public function testDoNothingIfThereAreNotUndeliveredOrders(): void
    {
        $this->artisan(ProcessOrdersCommand::class)
            ->expectsOutput('There are no orders that can be processed right now')
        ;
    }

    public function testRevertDataOnServiceException(): void
    {
        Http::fake(static fn () => Http::Response(null, 400));
        Ingredient::factory()->state(['amount' => 1000])->count(12)->create();
        Order::factory()
            ->has(OrderIngredient::factory()->count(3))
            ->count(5)
            ->create()
        ;

        $this->artisan(ProcessOrdersCommand::class)
            ->expectsOutput('The order could no be delivered')
            ->expectsOutput('Reverting changes...')
        ;

        Order::all()->each(function (Order $order) {
            $this->assertFalse($order->is_delivered);
        });

        Ingredient::all()->each(function (Ingredient $ingredient) {
            $this->assertSame(1000, $ingredient->amount);
        });
    }
}
