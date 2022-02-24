<?php

declare(strict_types=1);

namespace Tests\Feature\Commands;

use App\Console\Commands\BuyIngredientsCommand;
use App\Models\Ingredient;
use App\Models\IngredientPurchase;
use App\Models\Order;
use App\Models\OrderIngredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @covers \App\Console\Commands\BuyIngredientsCommand
 *
 * @internal
 */
final class BuyIngredientsCommandTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = false;

    public function testProcessOnlyUndeliveredOrders(): void
    {
        Http::fake(static fn () => Http::response(['quantitySold' => 5]));
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

        $this->artisan(BuyIngredientsCommand::class)
            ->expectsOutput('Amount of orders that need to be processed: 5')
        ;
    }

    public function testProcessAllTheRequiredIngredients(): void
    {
        Http::fake(static fn () => Http::response(['quantitySold' => 5]));
        Order::factory()
            ->has(
                OrderIngredient::factory()
                    ->state(function ($attributes): array {
                        /** @var Ingredient $ingredient */
                        $ingredient = Ingredient::factory()->create();

                        return ['ingredient_id' => $ingredient->id];
                    }),
            )
            ->count(5)
            ->create()
        ;

        $this->artisan(BuyIngredientsCommand::class)
            ->expectsTable(
                ['id', 'name'],
                Ingredient::all()->map(fn (Ingredient $ingredient): array => [$ingredient->id, $ingredient->name]),
            )
        ;
    }

    public function testStoreThePurchase(): void
    {
        Http::fake(static fn () => Http::response(['quantitySold' => 5]));
        $this->normalSeed();

        $this->artisan(BuyIngredientsCommand::class)->execute();

        IngredientPurchase::all()->each(function (IngredientPurchase $ingredientPurchase) {
            $this->assertSame(5, $ingredientPurchase->purchased_amount);
        });
    }

    public function testIncreaseIngredientsAmount(): void
    {
        Http::fake(static fn () => Http::response(['quantitySold' => 5]));
        Order::factory()
            ->has(
                OrderIngredient::factory()
                    ->state(function ($attributes): array {
                        /** @var Ingredient $ingredient */
                        $ingredient = Ingredient::factory()->create();

                        return ['ingredient_id' => $ingredient->id];
                    }),
            )
            ->count(5)
            ->create()
        ;

        $this->artisan(BuyIngredientsCommand::class)->execute();

        Ingredient::all()->each(function (Ingredient $ingredient) {
            $this->assertSame(10, $ingredient->amount);
        });
    }

    public function testDoNotProcessAnIngredientTwice(): void
    {
        Http::fake(static fn () => Http::response(['quantitySold' => 5]));
        Ingredient::factory()->count(2)->create();
        Order::factory()
            ->has(OrderIngredient::factory()->count(3))
            ->count(3)
            ->create()
        ;

        $this->artisan(BuyIngredientsCommand::class)
            ->expectsTable(
                ['id', 'name'],
                Ingredient::all()->map(fn (Ingredient $ingredient): array => [$ingredient->id, $ingredient->name]),
            )
        ;
    }

    public function testDoNothingIfThereAreNotUndeliveredOrders(): void
    {
        $this->artisan(BuyIngredientsCommand::class)
            ->expectsOutput('There are no undelivered orders so there is no need to buy ingredients')
        ;
    }

    public function testHandleServiceException(): void
    {
        Http::fake(static fn () => Http::response(['quantitySold' => false]));
        $this->normalSeed();

        $this->artisan(BuyIngredientsCommand::class)
            ->expectsOutput('The following error was handled: Expected an integer, received another data type')
            ->assertSuccessful()
        ;
    }

    public function normalSeed(): void
    {
        Ingredient::factory()->count(12)->create();
        Order::factory()
            ->has(OrderIngredient::factory()->count(3))
            ->count(5)
            ->create()
        ;
    }
}
