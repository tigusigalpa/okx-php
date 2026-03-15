<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Fiat extends BaseAPI
{
    public function getBuySellHistory(?string $ordId = null, ?string $after = null, ?string $before = null, ?string $begin = null, ?string $end = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ordId' => $ordId,
            'after' => $after,
            'before' => $before,
            'begin' => $begin,
            'end' => $end,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/fiat/buy-sell/history', ['query' => $query]);
    }

    public function cancelDeposit(string $ordId): array
    {
        return $this->client->request('GET', '/api/v5/fiat/cancel-deposit', [
            'query' => ['ordId' => $ordId],
        ]);
    }

    public function getChannels(string $ccy, string $paymentMethod, ?string $country = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'paymentMethod' => $paymentMethod,
            'country' => $country,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/fiat/channels', ['query' => $query]);
    }

    public function createDeposit(string $channelId, string $ccy, string $amt, ?string $country = null, ?string $beneficiary = null): array
    {
        $query = array_filter([
            'channelId' => $channelId,
            'ccy' => $ccy,
            'amt' => $amt,
            'country' => $country,
            'beneficiary' => $beneficiary,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/fiat/create-deposit', ['query' => $query]);
    }

    public function getDeposit(string $ordId): array
    {
        return $this->client->request('GET', '/api/v5/fiat/deposit', [
            'query' => ['ordId' => $ordId],
        ]);
    }

    public function getDepositHistory(?string $ordId = null, ?string $after = null, ?string $before = null, ?string $begin = null, ?string $end = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ordId' => $ordId,
            'after' => $after,
            'before' => $before,
            'begin' => $begin,
            'end' => $end,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/fiat/deposit-history', ['query' => $query]);
    }

    public function getDepositPaymentInfo(string $ordId): array
    {
        return $this->client->request('GET', '/api/v5/fiat/deposit-payment-info', [
            'query' => ['ordId' => $ordId],
        ]);
    }

    public function getPayments(?string $ccy = null, ?string $country = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'country' => $country,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/fiat/payments', ['query' => $query]);
    }

    public function getWithdrawalHistory(?string $ordId = null, ?string $after = null, ?string $before = null, ?string $begin = null, ?string $end = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ordId' => $ordId,
            'after' => $after,
            'before' => $before,
            'begin' => $begin,
            'end' => $end,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/fiat/withdrawal-history', ['query' => $query]);
    }

    public function buySellQuote(string $side, string $cryptoCcy, string $fiatCcy, string $paymentMethod, string $rfqSz, string $rfqSzCcy): array
    {
        return $this->client->request('POST', '/api/v5/fiat/buy-sell/quote', [
            'json' => [
                'side' => $side,
                'cryptoCcy' => $cryptoCcy,
                'fiatCcy' => $fiatCcy,
                'paymentMethod' => $paymentMethod,
                'rfqSz' => $rfqSz,
                'rfqSzCcy' => $rfqSzCcy,
            ],
        ]);
    }

    public function buySellTrade(string $quoteId, string $side, string $cryptoCcy, string $fiatCcy, string $paymentMethod, string $sz, string $szCcy): array
    {
        return $this->client->request('POST', '/api/v5/fiat/buy-sell/trade', [
            'json' => [
                'quoteId' => $quoteId,
                'side' => $side,
                'cryptoCcy' => $cryptoCcy,
                'fiatCcy' => $fiatCcy,
                'paymentMethod' => $paymentMethod,
                'sz' => $sz,
                'szCcy' => $szCcy,
            ],
        ]);
    }

    public function cancelWithdrawal(string $ordId): array
    {
        return $this->client->request('POST', '/api/v5/fiat/cancel-withdrawal', [
            'json' => ['ordId' => $ordId],
        ]);
    }

    public function createWithdrawal(string $channelId, string $ccy, string $amt, string $country, string $beneficiary): array
    {
        return $this->client->request('POST', '/api/v5/fiat/create-withdrawal', [
            'json' => [
                'channelId' => $channelId,
                'ccy' => $ccy,
                'amt' => $amt,
                'country' => $country,
                'beneficiary' => $beneficiary,
            ],
        ]);
    }
}
