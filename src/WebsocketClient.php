<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use WebSocket\Client as WsClient;
use WebSocket\ConnectionException;

class WebsocketClient
{
    private const PING_INTERVAL = 25;
    private const PONG_TIMEOUT = 30;

    private ?WsClient $connection = null;
    private array $subscriptions = [];
    private bool $isRunning = false;
    private ?int $lastPingTime = null;
    private LoggerInterface $logger;
    private Signer $signer;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $secretKey,
        private readonly string $passphrase,
        private readonly bool $isDemo = false,
        ?LoggerInterface $logger = null,
    ) {
        $this->logger = $logger ?? new NullLogger();
        $this->signer = new Signer($this->secretKey);
    }

    public function connectPublic(): void
    {
        $url = $this->isDemo
            ? 'wss://wspap.okx.com:8443/ws/v5/public'
            : 'wss://ws.okx.com:8443/ws/v5/public';

        $this->connect($url, false);
    }

    public function connectPrivate(): void
    {
        $url = $this->isDemo
            ? 'wss://wspap.okx.com:8443/ws/v5/private'
            : 'wss://ws.okx.com:8443/ws/v5/private';

        $this->connect($url, true);
    }

    public function connectBusiness(): void
    {
        $url = $this->isDemo
            ? 'wss://wspap.okx.com:8443/ws/v5/business'
            : 'wss://ws.okx.com:8443/ws/v5/business';

        $this->connect($url, true);
    }

    private function connect(string $url, bool $requiresAuth): void
    {
        try {
            $this->connection = new WsClient($url);
            $this->logger->info('WebSocket connected', ['url' => $url]);

            if ($requiresAuth) {
                $this->authenticate();
            }

            $this->isRunning = true;
            $this->lastPingTime = time();
        } catch (ConnectionException $e) {
            $this->logger->error('WebSocket connection failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    private function authenticate(): void
    {
        $timestamp = (string) time();
        $sign = $this->signer->sign($timestamp, 'GET', '/users/self/verify');

        $loginMessage = [
            'op' => 'login',
            'args' => [[
                'apiKey' => $this->apiKey,
                'passphrase' => $this->passphrase,
                'timestamp' => $timestamp,
                'sign' => $sign,
            ]],
        ];

        $this->send($loginMessage);
        $this->logger->info('WebSocket authentication sent');
    }

    public function subscribe(string $channel, array $args, callable $callback): void
    {
        $subscribeMessage = [
            'op' => 'subscribe',
            'args' => array_map(fn($arg) => array_merge(['channel' => $channel], $arg), [$args]),
        ];

        $this->send($subscribeMessage);

        $key = $this->getSubscriptionKey($channel, $args);
        $this->subscriptions[$key] = $callback;

        $this->logger->info('Subscribed to channel', [
            'channel' => $channel,
            'args' => $args,
        ]);
    }

    public function unsubscribe(string $channel, array $args): void
    {
        $unsubscribeMessage = [
            'op' => 'unsubscribe',
            'args' => array_map(fn($arg) => array_merge(['channel' => $channel], $arg), [$args]),
        ];

        $this->send($unsubscribeMessage);

        $key = $this->getSubscriptionKey($channel, $args);
        unset($this->subscriptions[$key]);

        $this->logger->info('Unsubscribed from channel', [
            'channel' => $channel,
            'args' => $args,
        ]);
    }

    public function run(): void
    {
        while ($this->isRunning && $this->connection !== null) {
            try {
                $this->handlePing();

                $message = $this->connection->receive();

                if ($message === 'pong') {
                    $this->logger->debug('Received pong');
                    continue;
                }

                $data = json_decode($message, true);

                if (isset($data['event'])) {
                    $this->handleEvent($data);
                } elseif (isset($data['arg']) && isset($data['data'])) {
                    $this->handleData($data);
                }
            } catch (ConnectionException $e) {
                $this->logger->error('WebSocket connection error', [
                    'error' => $e->getMessage(),
                ]);
                $this->reconnect();
            } catch (\Exception $e) {
                $this->logger->error('WebSocket error', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    public function stop(): void
    {
        $this->isRunning = false;

        if ($this->connection !== null) {
            $this->connection->close();
            $this->connection = null;
        }

        $this->logger->info('WebSocket stopped');
    }

    private function send(array $message): void
    {
        if ($this->connection === null) {
            throw new \RuntimeException('WebSocket not connected');
        }

        $this->connection->send(json_encode($message));
    }

    private function handlePing(): void
    {
        $now = time();

        if ($now - $this->lastPingTime >= self::PING_INTERVAL) {
            $this->connection?->send('ping');
            $this->lastPingTime = $now;
            $this->logger->debug('Sent ping');
        }
    }

    private function handleEvent(array $data): void
    {
        $event = $data['event'] ?? '';

        match ($event) {
            'login' => $this->logger->info('WebSocket login successful'),
            'subscribe' => $this->logger->info('Subscription confirmed', ['arg' => $data['arg'] ?? []]),
            'unsubscribe' => $this->logger->info('Unsubscription confirmed', ['arg' => $data['arg'] ?? []]),
            'error' => $this->logger->error('WebSocket error event', ['data' => $data]),
            default => $this->logger->debug('WebSocket event', ['event' => $event, 'data' => $data]),
        };
    }

    private function handleData(array $data): void
    {
        $arg = $data['arg'];
        $channel = $arg['channel'] ?? '';
        $key = $this->getSubscriptionKey($channel, $arg);

        if (isset($this->subscriptions[$key])) {
            try {
                ($this->subscriptions[$key])($data);
            } catch (\Exception $e) {
                $this->logger->error('Callback error', [
                    'channel' => $channel,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function getSubscriptionKey(string $channel, array $args): string
    {
        unset($args['channel']);
        ksort($args);
        return $channel . ':' . json_encode($args);
    }

    private function reconnect(): void
    {
        $this->logger->info('Attempting to reconnect...');

        $this->stop();

        sleep(5);

        try {
            $this->connectPublic();

            foreach ($this->subscriptions as $key => $callback) {
                [$channel, $argsJson] = explode(':', $key, 2);
                $args = json_decode($argsJson, true);
                $this->subscribe($channel, $args, $callback);
            }

            $this->logger->info('Reconnected successfully');
        } catch (\Exception $e) {
            $this->logger->error('Reconnection failed', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
