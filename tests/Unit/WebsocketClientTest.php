<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Tests\Unit;

use ReflectionMethod;
use ReflectionProperty;
use RuntimeException;
use Tigusigalpa\OKX\Tests\TestCase;
use Tigusigalpa\OKX\WebsocketClient;
use WebSocket\Client as NativeWebsocketClient;

final class WebsocketClientTest extends TestCase
{
    public function test_authentication_and_subscription_messages_are_sent_without_a_network_connection(): void
    {
        [$client, $connection] = $this->connectedClient();

        $this->callPrivate($client, 'authenticate');
        $client->subscribe('tickers', ['instId' => 'BTC-USDT'], static function (): void {
        });
        $client->unsubscribe('tickers', ['instId' => 'BTC-USDT']);

        self::assertSame('login', json_decode($connection->sent[0], true)['op']);
        self::assertSame(
            ['op' => 'subscribe', 'args' => [['channel' => 'tickers', 'instId' => 'BTC-USDT']]],
            json_decode($connection->sent[1], true)
        );
        self::assertSame(
            ['op' => 'unsubscribe', 'args' => [['channel' => 'tickers', 'instId' => 'BTC-USDT']]],
            json_decode($connection->sent[2], true)
        );
    }

    public function test_subscriptions_dispatch_data_and_isolate_callback_failures(): void
    {
        [$client] = $this->connectedClient();
        $received = [];

        $client->subscribe('trades', ['instId' => 'BTC-USDT'], static function (array $message) use (&$received): void {
            $received[] = $message;
        });
        $client->subscribe('books', ['instId' => 'BTC-USDT'], static function (): void {
            throw new RuntimeException('Callback failure');
        });

        $this->callPrivate($client, 'handleData', [[
            'arg' => ['channel' => 'trades', 'instId' => 'BTC-USDT'],
            'data' => [['px' => '50000']],
        ]]);
        $this->callPrivate($client, 'handleData', [[
            'arg' => ['channel' => 'books', 'instId' => 'BTC-USDT'],
            'data' => [],
        ]]);
        $this->callPrivate($client, 'handleData', [[
            'arg' => ['channel' => 'unsubscribed', 'instId' => 'BTC-USDT'],
            'data' => [],
        ]]);

        self::assertSame('50000', $received[0]['data'][0]['px']);
    }

    public function test_ping_events_and_stop_are_handled_without_connecting(): void
    {
        [$client, $connection] = $this->connectedClient();
        $this->setPrivate($client, 'lastPingTime', time() - 30);

        $this->callPrivate($client, 'handlePing');
        foreach (['login', 'subscribe', 'unsubscribe', 'error', 'unknown'] as $event) {
            $this->callPrivate($client, 'handleEvent', [['event' => $event, 'arg' => []]]);
        }

        $client->stop();

        self::assertSame('ping', $connection->sent[0]);
        self::assertTrue($connection->closed);
        self::assertNull($this->getPrivate($client, 'connection'));
    }

    public function test_operations_requiring_a_connection_fail_and_reconnect_without_state_is_a_noop(): void
    {
        $client = new WebsocketClient('api-key', 'secret-key', 'passphrase');

        $this->expectException(RuntimeException::class);
        try {
            $client->subscribe('tickers', ['instId' => 'BTC-USDT'], static function (): void {
            });
        } finally {
            $this->callPrivate($client, 'reconnect');
        }
    }

    /** @return array{WebsocketClient, FakeWebsocketConnection} */
    private function connectedClient(): array
    {
        $client = new WebsocketClient('api-key', 'secret-key', 'passphrase');
        $connection = new FakeWebsocketConnection();
        $this->setPrivate($client, 'connection', $connection);

        return [$client, $connection];
    }

    private function callPrivate(object $object, string $method, array $arguments = []): mixed
    {
        return (new ReflectionMethod($object, $method))->invokeArgs($object, $arguments);
    }

    private function setPrivate(object $object, string $property, mixed $value): void
    {
        (new ReflectionProperty($object, $property))->setValue($object, $value);
    }

    private function getPrivate(object $object, string $property): mixed
    {
        return (new ReflectionProperty($object, $property))->getValue($object);
    }
}

final class FakeWebsocketConnection extends NativeWebsocketClient
{
    /** @var list<string> */
    public array $sent = [];
    public bool $closed = false;

    public function __construct()
    {
    }

    public function send(string $message, string $opcode = 'text', bool $masked = true): void
    {
        $this->sent[] = $message;
    }

    public function close(int $status = 1000, string $message = 'ttfn'): void
    {
        $this->closed = true;
    }
}
