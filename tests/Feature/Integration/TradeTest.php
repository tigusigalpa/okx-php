<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Tests\Feature\Integration;

use Tigusigalpa\OKX\Client;
use Tigusigalpa\OKX\Tests\TestCase;

class TradeTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $apiKey = getenv('OKX_API_KEY');
        $secretKey = getenv('OKX_SECRET_KEY');
        $passphrase = getenv('OKX_PASSPHRASE');

        if (!$apiKey || !$secretKey || !$passphrase) {
            $this->markTestSkipped('OKX credentials not configured');
        }

        $this->client = new Client(
            apiKey: $apiKey,
            secretKey: $secretKey,
            passphrase: $passphrase,
            isDemo: true
        );
    }

    public function test_get_pending_orders(): void
    {
        $orders = $this->client->trade()->getOrdersPending();

        $this->assertIsArray($orders);
    }

    public function test_get_orders_history(): void
    {
        $orders = $this->client->trade()->getOrdersHistory(
            instType: 'SPOT',
            limit: 10
        );

        $this->assertIsArray($orders);
    }

    public function test_get_fills(): void
    {
        $fills = $this->client->trade()->getFills(
            instType: 'SPOT',
            limit: 10
        );

        $this->assertIsArray($fills);
    }
}
