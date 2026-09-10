<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Query;
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
    protected readonly string $baseUrl;
    protected readonly Region $region;

    public function __construct(
        protected readonly string $apiKey,
        protected readonly string $secretKey,
        protected readonly string $passphrase,
        protected readonly bool $isDemo = false,
        ?string $baseUrl = null,
        ?HttpClient $httpClient = null,
        ?LoggerInterface $logger = null,
        Region|string $region = Region::Global,
    ) {
        $this->region = Region::resolve($region);
        $this->baseUrl = rtrim($baseUrl ?: $this->region->restBaseUrl(), '/');
        $this->httpClient = $httpClient ?? new HttpClient(['base_uri' => $this->baseUrl]);
        $this->logger = $logger ?? new NullLogger();
        $this->signer = new Signer($this->secretKey);
    }

    public function baseUrl(): string
    {
        return $this->baseUrl;
    }

    public function region(): Region
    {
        return $this->region;
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
        $method = strtoupper($method);
        $timestamp = $this->signer->generateTimestamp();
        [$body, $options] = $this->prepareBody($options);
        $requestPath = $this->buildRequestPath($path, $options['query'] ?? null);

        $signature = $this->signer->sign($timestamp, $method, $requestPath, $body);

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
            'path' => $requestPath,
            'timestamp' => $timestamp,
        ]);

        try {
            $response = $this->httpClient->request($method, $path, $requestOptions);
            return $this->handleResponse((string) $response->getBody());
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response !== null) {
                return $this->handleResponse((string) $response->getBody());
            }

            $this->logRequestFailure($method, $requestPath, $e);
            throw new OKXException('HTTP_ERROR', $e->getMessage(), '', $e);
        } catch (GuzzleException $e) {
            $this->logRequestFailure($method, $requestPath, $e);
            throw new OKXException('HTTP_ERROR', $e->getMessage(), '', $e);
        }
    }

    /**
     * Serializes the payload once, so the bytes sent to OKX are the exact bytes
     * covered by the request signature.
     *
     * @return array{0: string, 1: array}
     */
    private function prepareBody(array $options): array
    {
        if (!array_key_exists('json', $options)) {
            return ['', $options];
        }

        try {
            $body = json_encode($options['json'], JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \InvalidArgumentException('Unable to encode the OKX request body as JSON.', 0, $e);
        }

        unset($options['json']);
        $options['body'] = $body;

        return [$body, $options];
    }

    private function buildRequestPath(string $path, array|string|null $query): string
    {
        if ($query === null || $query === [] || $query === '') {
            return $path;
        }

        $queryString = is_array($query)
            ? Query::build($query, PHP_QUERY_RFC3986)
            : $query;

        if ($queryString === '') {
            return $path;
        }

        return $path . (str_contains($path, '?') ? '&' : '?') . $queryString;
    }

    private function handleResponse(string $responseBody): array
    {
        try {
            $data = json_decode($responseBody, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new OKXException(
                'INVALID_RESPONSE',
                'OKX API returned an invalid JSON response.',
                $responseBody,
                $e
            );
        }

        if (!is_array($data)) {
            throw new OKXException('INVALID_RESPONSE', 'OKX API returned an unexpected response.', $responseBody);
        }

        $this->logger->debug('OKX API Response', [
            'code' => $data['code'] ?? 'unknown',
            'msg' => $data['msg'] ?? '',
        ]);

        if (($data['code'] ?? null) !== '0') {
            $this->throwException($data, $responseBody);
        }

        return $data['data'] ?? [];
    }

    private function logRequestFailure(string $method, string $requestPath, \Throwable $exception): void
    {
        $this->logger->error('OKX API Request Failed', [
            'method' => $method,
            'path' => $requestPath,
            'error' => $exception->getMessage(),
        ]);
    }

    protected function throwException(array $data, string $rawResponse): void
    {
        $code = (string) ($data['code'] ?? 'UNKNOWN');
        $message = (string) ($data['msg'] ?? 'Unknown error');

        $exceptionClass = match (true) {
            in_array($code, ['50111', '50113']) => AuthenticationException::class,
            $code === '50011' => RateLimitException::class,
            $code === '51008' => InsufficientFundsException::class,
            $code >= '51000' && $code < '51100' => InvalidParameterException::class,
            default => OKXException::class,
        };

        throw new $exceptionClass($code, $message, $rawResponse, null, $data);
    }
}
