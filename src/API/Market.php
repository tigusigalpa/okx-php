<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Market extends BaseAPI
{
    public function getBlockTicker(string $instId): array
    {
        return $this->client->request('GET', '/api/v5/market/block-ticker', [
            'query' => ['instId' => $instId],
        ]);
    }

    public function getBooks(string $instId, ?string $sz = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'sz' => $sz,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/books', ['query' => $query]);
    }

    public function getBooksFull(string $instId, ?string $sz = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'sz' => $sz,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/books-full', ['query' => $query]);
    }

    public function getBooksLite(string $instId): array
    {
        return $this->client->request('GET', '/api/v5/market/books-lite', [
            'query' => ['instId' => $instId],
        ]);
    }

    public function getBooksSbe(string $instId, ?string $sz = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'sz' => $sz,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/books-sbe', ['query' => $query]);
    }

    public function getCallAuctionDetails(string $instId): array
    {
        return $this->client->request('GET', '/api/v5/market/call-auction-details', [
            'query' => ['instId' => $instId],
        ]);
    }

    public function getCandles(string $instId, ?string $bar = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'bar' => $bar,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/candles', ['query' => $query]);
    }

    public function getExchangeRate(): array
    {
        return $this->client->request('GET', '/api/v5/market/exchange-rate');
    }

    public function getHistoryCandles(string $instId, ?string $bar = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'bar' => $bar,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/history-candles', ['query' => $query]);
    }

    public function getHistoryIndexCandles(string $instId, ?string $bar = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'bar' => $bar,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/history-index-candles', ['query' => $query]);
    }

    public function getHistoryMarkPriceCandles(string $instId, ?string $bar = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'bar' => $bar,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/history-mark-price-candles', ['query' => $query]);
    }

    public function getHistoryTrades(string $instId, ?string $type = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'type' => $type,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/history-trades', ['query' => $query]);
    }

    public function getIndexCandles(string $instId, ?string $bar = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'bar' => $bar,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/index-candles', ['query' => $query]);
    }

    public function getIndexComponents(string $index): array
    {
        return $this->client->request('GET', '/api/v5/market/index-components', [
            'query' => ['index' => $index],
        ]);
    }

    public function getIndexTickers(?string $quoteCcy = null, ?string $instId = null): array
    {
        $query = array_filter([
            'quoteCcy' => $quoteCcy,
            'instId' => $instId,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/index-tickers', ['query' => $query]);
    }

    public function getMarkPriceCandles(string $instId, ?string $bar = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'bar' => $bar,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/mark-price-candles', ['query' => $query]);
    }

    public function getOpenOracle(): array
    {
        return $this->client->request('GET', '/api/v5/market/open-oracle');
    }

    public function getOptionInstrumentFamilyTrades(string $instFamily): array
    {
        return $this->client->request('GET', '/api/v5/market/option/instrument-family-trades', [
            'query' => ['instFamily' => $instFamily],
        ]);
    }

    public function getPlatform24Volume(): array
    {
        return $this->client->request('GET', '/api/v5/market/platform-24-volume');
    }

    public function getTicker(string $instId): array
    {
        return $this->client->request('GET', '/api/v5/market/ticker', [
            'query' => ['instId' => $instId],
        ]);
    }

    public function getTickers(string $instType, ?string $uly = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/tickers', ['query' => $query]);
    }

    public function getTrades(string $instId, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/trades', ['query' => $query]);
    }

    public function getTradesContract(string $instId, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/market/trades-contract', ['query' => $query]);
    }

    public function getUnderlying(string $instType): array
    {
        return $this->client->request('GET', '/api/v5/market/underlying', [
            'query' => ['instType' => $instType],
        ]);
    }
}
