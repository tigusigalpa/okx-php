<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Rubik extends BaseAPI
{
    public function getContractsLongShortAccountRatio(string $ccy, ?string $begin = null, ?string $end = null, ?string $period = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'begin' => $begin,
            'end' => $end,
            'period' => $period,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/contracts/long-short-account-ratio', ['query' => $query]);
    }

    public function getContractsLongShortAccountRatioContract(string $ccy, ?string $begin = null, ?string $end = null, ?string $period = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'begin' => $begin,
            'end' => $end,
            'period' => $period,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/contracts/long-short-account-ratio-contract', ['query' => $query]);
    }

    public function getContractsLongShortAccountRatioContractTopTrader(string $ccy, ?string $begin = null, ?string $end = null, ?string $period = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'begin' => $begin,
            'end' => $end,
            'period' => $period,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/contracts/long-short-account-ratio-contract-top-trader', ['query' => $query]);
    }

    public function getContractsLongShortPositionRatioContractTopTrader(string $ccy, ?string $begin = null, ?string $end = null, ?string $period = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'begin' => $begin,
            'end' => $end,
            'period' => $period,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/contracts/long-short-position-ratio-contract-top-trader', ['query' => $query]);
    }

    public function getContractsOpenInterestHistory(string $ccy, ?string $begin = null, ?string $end = null, ?string $period = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'begin' => $begin,
            'end' => $end,
            'period' => $period,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/contracts/open-interest-history', ['query' => $query]);
    }

    public function getContractsOpenInterestVolume(string $ccy, ?string $begin = null, ?string $end = null, ?string $period = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'begin' => $begin,
            'end' => $end,
            'period' => $period,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/contracts/open-interest-volume', ['query' => $query]);
    }

    public function getMarginLoanRatio(string $ccy, ?string $begin = null, ?string $end = null, ?string $period = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'begin' => $begin,
            'end' => $end,
            'period' => $period,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/margin/loan-ratio', ['query' => $query]);
    }

    public function getOptionOpenInterestVolume(string $ccy, ?string $period = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'period' => $period,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/option/open-interest-volume', ['query' => $query]);
    }

    public function getOptionOpenInterestVolumeExpiry(string $ccy, ?string $period = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'period' => $period,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/option/open-interest-volume-expiry', ['query' => $query]);
    }

    public function getOptionOpenInterestVolumeRatio(string $ccy, ?string $period = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'period' => $period,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/option/open-interest-volume-ratio', ['query' => $query]);
    }

    public function getOptionOpenInterestVolumeStrike(string $ccy, string $expTime, ?string $period = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'expTime' => $expTime,
            'period' => $period,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/option/open-interest-volume-strike', ['query' => $query]);
    }

    public function getOptionTakerBlockVolume(string $ccy, ?string $period = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'period' => $period,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/option/taker-block-volume', ['query' => $query]);
    }

    public function getTakerVolume(string $ccy, string $instType, ?string $begin = null, ?string $end = null, ?string $period = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'instType' => $instType,
            'begin' => $begin,
            'end' => $end,
            'period' => $period,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/taker-volume', ['query' => $query]);
    }

    public function getTakerVolumeContract(string $ccy, ?string $begin = null, ?string $end = null, ?string $period = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'begin' => $begin,
            'end' => $end,
            'period' => $period,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rubik/stat/taker-volume-contract', ['query' => $query]);
    }

    public function getTradingDataSupportCoin(): array
    {
        return $this->client->request('GET', '/api/v5/rubik/stat/trading-data/support-coin');
    }
}
