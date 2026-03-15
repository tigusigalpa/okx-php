<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Tests\Feature\Integration;

use Tigusigalpa\OKX\Client;
use Tigusigalpa\OKX\Tests\TestCase;

class AccountTest extends TestCase
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

    public function test_get_account_balance(): void
    {
        $balance = $this->client->account()->getBalance();

        $this->assertIsArray($balance);
    }

    public function test_get_account_config(): void
    {
        $config = $this->client->account()->getConfig();

        $this->assertIsArray($config);
    }

    public function test_get_positions(): void
    {
        $positions = $this->client->account()->getPositions();

        $this->assertIsArray($positions);
    }
}
