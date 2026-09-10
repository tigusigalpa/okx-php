<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Tests\Unit;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Tigusigalpa\OKX\Client;
use Tigusigalpa\OKX\Exceptions\AuthenticationException;
use Tigusigalpa\OKX\Exceptions\OKXException;
use Tigusigalpa\OKX\Exceptions\RateLimitException;
use Tigusigalpa\OKX\Signer;
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

    public function test_get_request_signs_the_encoded_query_string(): void
    {
        $history = [];
        $mock = new MockHandler([
            new Response(200, [], json_encode(['code' => '0', 'msg' => '', 'data' => []])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push(Middleware::history($history));

        $client = new Client(
            apiKey: 'test-api-key',
            secretKey: 'test-secret-key',
            passphrase: 'test-passphrase',
            httpClient: new HttpClient(['handler' => $handlerStack])
        );

        $client->account()->getBalance('BTC');

        $request = $history[0]['request'];
        $requestPath = '/api/v5/account/balance?ccy=BTC';
        $timestamp = $request->getHeaderLine('OK-ACCESS-TIMESTAMP');

        $this->assertSame($requestPath, $request->getRequestTarget());
        $this->assertSame(
            (new Signer('test-secret-key'))->sign($timestamp, 'GET', $requestPath),
            $request->getHeaderLine('OK-ACCESS-SIGN')
        );
    }

    public function test_post_request_signs_the_exact_json_body_sent_to_okx(): void
    {
        $history = [];
        $mock = new MockHandler([
            new Response(200, [], json_encode(['code' => '0', 'msg' => '', 'data' => []])),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $handlerStack->push(Middleware::history($history));

        $client = new Client(
            apiKey: 'test-api-key',
            secretKey: 'test-secret-key',
            passphrase: 'test-passphrase',
            httpClient: new HttpClient(['handler' => $handlerStack])
        );

        $client->request('POST', '/api/v5/trade/order', [
            'json' => ['instId' => 'BTC-USDT', 'sz' => '0.01'],
        ]);

        $request = $history[0]['request'];
        $body = (string) $request->getBody();
        $timestamp = $request->getHeaderLine('OK-ACCESS-TIMESTAMP');

        $this->assertSame('{"instId":"BTC-USDT","sz":"0.01"}', $body);
        $this->assertSame(
            (new Signer('test-secret-key'))->sign($timestamp, 'POST', '/api/v5/trade/order', $body),
            $request->getHeaderLine('OK-ACCESS-SIGN')
        );
    }

    public function test_detailed_order_error_is_available_on_the_exception(): void
    {
        $rawResponse = json_encode([
            'code' => '1',
            'msg' => 'All operations failed',
            'data' => [[
                'sCode' => '54070',
                'sMsg' => 'Use the attachAlgoOrds array to place orders via Open API',
            ]],
        ]);
        $mock = new MockHandler([new Response(200, [], $rawResponse)]);

        $client = new Client(
            apiKey: 'test-api-key',
            secretKey: 'test-secret-key',
            passphrase: 'test-passphrase',
            httpClient: new HttpClient(['handler' => HandlerStack::create($mock)])
        );

        try {
            $client->request('POST', '/api/v5/trade/order', ['json' => []]);
            $this->fail('Expected an OKX exception.');
        } catch (OKXException $e) {
            $this->assertSame('1', $e->okxCode);
            $this->assertSame($rawResponse, $e->rawResponse);
            $this->assertSame('54070', $e->response['data'][0]['sCode']);
        }
    }

    public function test_http_error_with_an_okx_envelope_preserves_api_error_details(): void
    {
        $rawResponse = json_encode([
            'code' => '50111',
            'msg' => 'Invalid API key',
            'data' => [],
        ]);
        $mock = new MockHandler([new Response(401, [], $rawResponse)]);

        $client = new Client(
            apiKey: 'test-api-key',
            secretKey: 'test-secret-key',
            passphrase: 'test-passphrase',
            httpClient: new HttpClient(['handler' => HandlerStack::create($mock)])
        );

        try {
            $client->request('GET', '/api/v5/account/balance');
            $this->fail('Expected an authentication exception.');
        } catch (AuthenticationException $e) {
            $this->assertSame('50111', $e->okxCode);
            $this->assertSame($rawResponse, $e->rawResponse);
            $this->assertSame('Invalid API key', $e->response['msg']);
        }
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
