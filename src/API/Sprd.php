<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Sprd extends BaseAPI
{
    public function getBooks(string $sprdId, ?string $sz = null): array
    {
        $query = array_filter([
            'sprdId' => $sprdId,
            'sz' => $sz,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/sprd/books', ['query' => $query]);
    }

    public function getOrder(?string $ordId = null, ?string $clOrdId = null): array
    {
        $query = array_filter([
            'ordId' => $ordId,
            'clOrdId' => $clOrdId,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/sprd/order', ['query' => $query]);
    }

    public function getOrdersHistory(?string $sprdId = null, ?string $ordType = null, ?string $state = null, ?string $beginId = null, ?string $endId = null, ?int $limit = null): array
    {
        $query = array_filter([
            'sprdId' => $sprdId,
            'ordType' => $ordType,
            'state' => $state,
            'beginId' => $beginId,
            'endId' => $endId,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/sprd/orders-history', ['query' => $query]);
    }

    public function getOrdersHistoryArchive(?string $sprdId = null, ?string $ordType = null, ?string $state = null, ?string $beginId = null, ?string $endId = null, ?int $limit = null): array
    {
        $query = array_filter([
            'sprdId' => $sprdId,
            'ordType' => $ordType,
            'state' => $state,
            'beginId' => $beginId,
            'endId' => $endId,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/sprd/orders-history-archive', ['query' => $query]);
    }

    public function getOrdersPending(?string $sprdId = null, ?string $ordType = null, ?string $state = null, ?string $beginId = null, ?string $endId = null, ?int $limit = null): array
    {
        $query = array_filter([
            'sprdId' => $sprdId,
            'ordType' => $ordType,
            'state' => $state,
            'beginId' => $beginId,
            'endId' => $endId,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/sprd/orders-pending', ['query' => $query]);
    }

    public function getPublicTrades(string $sprdId, ?int $limit = null): array
    {
        $query = array_filter([
            'sprdId' => $sprdId,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/sprd/public-trades', ['query' => $query]);
    }

    public function getSpreads(?string $baseCcy = null, ?string $instId = null, ?string $sprdId = null, ?string $state = null): array
    {
        $query = array_filter([
            'baseCcy' => $baseCcy,
            'instId' => $instId,
            'sprdId' => $sprdId,
            'state' => $state,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/sprd/spreads', ['query' => $query]);
    }

    public function getTrades(?string $sprdId = null, ?string $ordId = null, ?string $beginId = null, ?string $endId = null, ?int $limit = null): array
    {
        $query = array_filter([
            'sprdId' => $sprdId,
            'ordId' => $ordId,
            'beginId' => $beginId,
            'endId' => $endId,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/sprd/trades', ['query' => $query]);
    }

    public function amendOrder(string $ordId, ?string $newSz = null, ?string $newPx = null): array
    {
        $data = array_filter([
            'ordId' => $ordId,
            'newSz' => $newSz,
            'newPx' => $newPx,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/sprd/amend-order', ['json' => $data]);
    }

    public function cancelAllAfter(string $timeOut): array
    {
        return $this->client->request('POST', '/api/v5/sprd/cancel-all-after', [
            'json' => ['timeOut' => $timeOut],
        ]);
    }

    public function cancelOrder(string $ordId): array
    {
        return $this->client->request('POST', '/api/v5/sprd/cancel-order', [
            'json' => ['ordId' => $ordId],
        ]);
    }

    public function massCancel(?string $sprdId = null): array
    {
        $data = [];
        if ($sprdId !== null) {
            $data['sprdId'] = $sprdId;
        }
        return $this->client->request('POST', '/api/v5/sprd/mass-cancel', ['json' => $data]);
    }

    public function placeOrder(string $sprdId, string $side, string $ordType, string $sz, ?string $px = null, ?string $clOrdId = null): array
    {
        $data = array_filter([
            'sprdId' => $sprdId,
            'side' => $side,
            'ordType' => $ordType,
            'sz' => $sz,
            'px' => $px,
            'clOrdId' => $clOrdId,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/sprd/order', ['json' => $data]);
    }
}
