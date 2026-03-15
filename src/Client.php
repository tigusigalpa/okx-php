<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Tigusigalpa\OKX\API;
use Tigusigalpa\OKX\Exceptions\AuthenticationException;
use Tigusigalpa\OKX\Exceptions\InsufficientFundsException;
use Tigusigalpa\OKX\Exceptions\InvalidParameterException;
use Tigusigalpa\OKX\Exceptions\OKXException;
use Tigusigalpa\OKX\Exceptions\RateLimitException;

class Client
{
    protected HttpClient $httpClient;
    protected LoggerInterface $logger;
    protected Signer $signer;

    public function __construct(
        protected readonly string $apiKey,
        protected readonly string $secretKey,
        protected readonly string $passphrase,
        protected readonly bool $isDemo = false,
        protected readonly string $baseUrl = 'https://www.okx.com',
        ?HttpClient $httpClient = null,
        ?LoggerInterface $logger = null,
    ) {
        $this->httpClient = $httpClient ?? new HttpClient(['base_uri' => $this->baseUrl]);
        $this->logger = $logger ?? new NullLogger();
        $this->signer = new Signer($this->secretKey);
    }

    public function account(): API\Account
    {
        return new API\Account($this);
    }

    public function affiliate(): API\Affiliate
    {
        return new API\Affiliate($this);
    }

    public function asset(): API\Asset
    {
        return new API\Asset($this);
    }

    public function copyTrading(): API\CopyTrading
    {
        return new API\CopyTrading($this);
    }

    public function fiat(): API\Fiat
    {
        return new API\Fiat($this);
    }

    public function finance(): API\Finance
    {
        return new API\Finance($this);
    }

    public function market(): API\Market
    {
        return new API\Market($this);
    }

    public function publicData(): API\PublicData
    {
        return new API\PublicData($this);
    }

    public function rfq(): API\RFQ
    {
        return new API\RFQ($this);
    }

    public function rubik(): API\Rubik
    {
        return new API\Rubik($this);
    }

    public function sprd(): API\Sprd
    {
        return new API\Sprd($this);
    }

    public function support(): API\Support
    {
        return new API\Support($this);
    }

    public function systemStatus(): API\SystemStatus
    {
        return new API\SystemStatus($this);
    }

    public function trade(): API\Trade
    {
        return new API\Trade($this);
    }

    public function tradingBot(): API\TradingBot
    {
        return new API\TradingBot($this);
    }

    public function users(): API\Users
    {
        return new API\Users($this);
    }

    public function request(string $method, string $path, array $options = []): array
    {
        $timestamp = $this->signer->generateTimestamp();
        $body = '';

        if (strtoupper($method) === 'POST' && isset($options['json'])) {
            $body = json_encode($options['json']);
        }

        $signature = $this->signer->sign($timestamp, $method, $path, $body);

        $headers = [
            'OK-ACCESS-KEY' => $this->apiKey,
            'OK-ACCESS-SIGN' => $signature,
            'OK-ACCESS-TIMESTAMP' => $timestamp,
            'OK-ACCESS-PASSPHRASE' => $this->passphrase,
            'Content-Type' => 'application/json',
        ];

        if ($this->isDemo) {
            $headers['x-simulated-trading'] = '1';
        }

        $requestOptions = array_merge($options, ['headers' => $headers]);

        $this->logger->debug('OKX API Request', [
            'method' => $method,
            'path' => $path,
            'timestamp' => $timestamp,
        ]);

        try {
            $response = $this->httpClient->request($method, $path, $requestOptions);
            $responseBody = (string) $response->getBody();
            $data = json_decode($responseBody, true);

            $this->logger->debug('OKX API Response', [
                'code' => $data['code'] ?? 'unknown',
                'msg' => $data['msg'] ?? '',
            ]);

            if (!isset($data['code']) || $data['code'] !== '0') {
                $this->throwException($data, $responseBody);
            }

            return $data['data'] ?? [];
        } catch (GuzzleException $e) {
            $this->logger->error('OKX API Request Failed', [
                'method' => $method,
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            throw new OKXException('HTTP_ERROR', $e->getMessage(), '', $e);
        }
    }

    protected function throwException(array $data, string $rawResponse): void
    {
        $code = $data['code'] ?? 'UNKNOWN';
        $message = $data['msg'] ?? 'Unknown error';

        $exceptionClass = match (true) {
            in_array($code, ['50111', '50113']) => AuthenticationException::class,
            $code === '50011' => RateLimitException::class,
            $code >= '51000' && $code < '51100' => InvalidParameterException::class,
            $code >= '54000' && $code < '54100' => InsufficientFundsException::class,
            default => OKXException::class,
        };

        throw new $exceptionClass($code, $message, $rawResponse);
    }
}
