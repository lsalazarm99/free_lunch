<?php

declare(strict_types=1);

namespace App\Services\KitchenService;

use App\Models\Order;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class KitchenService
{
    public PendingRequest $client;

    public function __construct(string $domain, string $protocol = 'http')
    {
        $this->client = Http::baseUrl("{$protocol}://{$domain}");
    }

    /**
     * @param Order $order The order which ingredients are going to be delivered
     *
     * @throws RequestException
     */
    public function deliverIngredients(Order $order): void
    {
        $this->client->put("/order/deliverIngredients/{$order->id}")->throw();
    }
}
