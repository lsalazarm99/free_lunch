<?php

declare(strict_types=1);

namespace App\Services\FoodShopService;

use App\Models\Ingredient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

final class FoodShopService
{
    public PendingRequest $client;

    public function __construct(string $domain, string $protocol = 'http')
    {
        $this->client = Http::baseUrl("{$protocol}://{$domain}");
    }

    /**
     * @throws RequestException
     * @throws RuntimeException
     *
     * @return int the amount of ingredients bought
     */
    public function buyIngredient(Ingredient $ingredient): int
    {
        $response = $this->client->get('farmers-market/buy', ['ingredient' => $ingredient->code])->throw();

        $amountBough = $response->json('quantitySold');

        if (!is_int($amountBough)) {
            throw new RuntimeException('Expected an integer, received another data type');
        }

        return $amountBough;
    }
}
