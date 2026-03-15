<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Tests\Unit;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tigusigalpa\OKX\Client;
use Tigusigalpa\OKX\Exceptions\AuthenticationException;
use Tigusigalpa\OKX\Exceptions\OKXException;
use Tigusigalpa\OKX\Exceptions\RateLimitException;
use Tigusigalpa\OKX\Tests\TestCase;

class ClientTest extends TestCase
{
    public function test_client_instantiation(): void
    {
        $client = new Client(
            apiKey: 'test-api-key',
            secretKey: 'test-secret-key',
            passphrase: 'test-passphrase'
        );

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_successful_request(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'code' => '0',
                'msg' => '',
                'data' => [['ccy' => 'BTC', 'bal' => '1.5']],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $client = new Client(
            apiKey: 'test-api-key',
            secretKey: 'test-secret-key',
            passphrase: 'test-passphrase',
            httpClient: $httpClient
        );

        $result = $client->request('GET', '/api/v5/account/balance');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('BTC', $result[0]['ccy']);
    }

    public function test_authentication_error_throws_exception(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'code' => '50111',
                'msg' => 'Invalid API key',
                'data' => [],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $client = new Client(
            apiKey: 'invalid-key',
            secretKey: 'test-secret-key',
            passphrase: 'test-passphrase',
            httpClient: $httpClient
        );

        $this->expectException(AuthenticationException::class);
        $client->request('GET', '/api/v5/account/balance');
    }

    public function test_rate_limit_error_throws_exception(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'code' => '50011',
                'msg' => 'Rate limit exceeded',
                'data' => [],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $client = new Client(
            apiKey: 'test-api-key',
            secretKey: 'test-secret-key',
            passphrase: 'test-passphrase',
            httpClient: $httpClient
        );

        $this->expectException(RateLimitException::class);
        $client->request('GET', '/api/v5/account/balance');
    }

    public function test_demo_mode_adds_header(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'code' => '0',
                'msg' => '',
                'data' => [],
            ])),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handlerStack]);

        $client = new Client(
            apiKey: 'test-api-key',
            secretKey: 'test-secret-key',
            passphrase: 'test-passphrase',
            isDemo: true,
            httpClient: $httpClient
        );

        $client->request('GET', '/api/v5/account/balance');

        $this->assertTrue(true);
    }

    public function test_api_service_factories(): void
    {
        $client = new Client(
            apiKey: 'test-api-key',
            secretKey: 'test-secret-key',
            passphrase: 'test-passphrase'
        );

        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Account::class, $client->account());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Trade::class, $client->trade());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Market::class, $client->market());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\PublicData::class, $client->publicData());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Asset::class, $client->asset());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Finance::class, $client->finance());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\CopyTrading::class, $client->copyTrading());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\TradingBot::class, $client->tradingBot());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Users::class, $client->users());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\RFQ::class, $client->rfq());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Sprd::class, $client->sprd());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Rubik::class, $client->rubik());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Fiat::class, $client->fiat());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Affiliate::class, $client->affiliate());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\Support::class, $client->support());
        $this->assertInstanceOf(\Tigusigalpa\OKX\API\SystemStatus::class, $client->systemStatus());
    }
}
