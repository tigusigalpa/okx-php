<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class PublicData extends BaseAPI
{
    public function getBlockVolume(): array
    {
        return $this->client->request('GET', '/api/v5/public/block-volume');
    }

    public function convertContractCoin(?string $type = null, ?string $instId = null, ?string $sz = null, ?string $px = null, ?string $unit = null): array
    {
        $query = array_filter([
            'type' => $type,
            'instId' => $instId,
            'sz' => $sz,
            'px' => $px,
            'unit' => $unit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/convert-contract-coin', ['query' => $query]);
    }

    public function getDeliveryExerciseHistory(string $instType, ?string $uly = null, ?string $after = null, ?string $before = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/delivery-exercise-history', ['query' => $query]);
    }

    public function getDiscountRateInterestFreeQuota(?string $ccy = null, ?string $discountLv = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'discountLv' => $discountLv,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/discount-rate-interest-free-quota', ['query' => $query]);
    }

    public function getEconomicCalendar(?string $region = null, ?string $importance = null, ?string $before = null, ?string $after = null, ?int $limit = null): array
    {
        $query = array_filter([
            'region' => $region,
            'importance' => $importance,
            'before' => $before,
            'after' => $after,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/economic-calendar', ['query' => $query]);
    }

    public function getEstimatedDeliveryExercisePrice(?string $instId = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/estimated-delivery-exercise-price', ['query' => $query]);
    }

    public function getFundingRate(string $instId): array
    {
        return $this->client->request('GET', '/api/v5/public/funding-rate', [
            'query' => ['instId' => $instId],
        ]);
    }

    public function getFundingRateHistory(string $instId, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/funding-rate-history', ['query' => $query]);
    }

    public function getIndexTickers(?string $quoteCcy = null, ?string $instId = null): array
    {
        $query = array_filter([
            'quoteCcy' => $quoteCcy,
            'instId' => $instId,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/index-tickers', ['query' => $query]);
    }

    public function getInstrumentTickBands(string $instType, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/instrument-tick-bands', ['query' => $query]);
    }

    public function getInstruments(string $instType, ?string $uly = null, ?string $instFamily = null, ?string $instId = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'instFamily' => $instFamily,
            'instId' => $instId,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/instruments', ['query' => $query]);
    }

    public function getInsuranceFund(string $instType, ?string $type = null, ?string $uly = null, ?string $ccy = null, ?string $before = null, ?string $after = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'type' => $type,
            'uly' => $uly,
            'ccy' => $ccy,
            'before' => $before,
            'after' => $after,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/insurance-fund', ['query' => $query]);
    }

    public function getInterestRateLoanQuota(): array
    {
        return $this->client->request('GET', '/api/v5/public/interest-rate-loan-quota');
    }

    public function getLiquidationOrders(string $instType, ?string $mgnMode = null, ?string $instId = null, ?string $ccy = null, ?string $uly = null, ?string $alias = null, ?string $state = null, ?string $before = null, ?string $after = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'mgnMode' => $mgnMode,
            'instId' => $instId,
            'ccy' => $ccy,
            'uly' => $uly,
            'alias' => $alias,
            'state' => $state,
            'before' => $before,
            'after' => $after,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/liquidation-orders', ['query' => $query]);
    }

    public function getMarkPrice(string $instType, ?string $uly = null, ?string $instId = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'instId' => $instId,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/mark-price', ['query' => $query]);
    }

    public function getOpenInterest(string $instType, ?string $uly = null, ?string $instId = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'instId' => $instId,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/open-interest', ['query' => $query]);
    }

    public function getOptSummary(?string $uly = null, ?string $expTime = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'uly' => $uly,
            'expTime' => $expTime,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/opt-summary', ['query' => $query]);
    }

    public function getPositionTiers(string $instType, string $tdMode, ?string $uly = null, ?string $instFamily = null, ?string $instId = null, ?string $ccy = null, ?string $tier = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'tdMode' => $tdMode,
            'uly' => $uly,
            'instFamily' => $instFamily,
            'instId' => $instId,
            'ccy' => $ccy,
            'tier' => $tier,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/position-tiers', ['query' => $query]);
    }

    public function getPremiumHistory(?string $instId = null, ?string $instFamily = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'instFamily' => $instFamily,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/premium-history', ['query' => $query]);
    }

    public function getPriceLimit(string $instId): array
    {
        return $this->client->request('GET', '/api/v5/public/price-limit', [
            'query' => ['instId' => $instId],
        ]);
    }

    public function getSettlementHistory(string $instType, ?string $uly = null, ?string $after = null, ?string $before = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/public/settlement-history', ['query' => $query]);
    }

    public function getTime(): array
    {
        return $this->client->request('GET', '/api/v5/public/time');
    }

    public function getUnderlying(string $instType): array
    {
        return $this->client->request('GET', '/api/v5/public/underlying', [
            'query' => ['instType' => $instType],
        ]);
    }

    public function getVipInterestRateLoanQuota(): array
    {
        return $this->client->request('GET', '/api/v5/public/vip-interest-rate-loan-quota');
    }
}
