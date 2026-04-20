<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Asset extends BaseAPI
{
    public function getAssetValuation(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/asset/asset-valuation', $options);
    }

    public function getBalances(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/asset/balances', $options);
    }

    public function getBills(?string $ccy = null, ?string $type = null, ?string $after = null, ?string $before = null, ?int $limit = null, ?string $clientId = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'type' => $type,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'clientId' => $clientId,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/asset/bills', ['query' => $query]);
    }

    public function getConvertCurrencies(): array
    {
        return $this->client->request('GET', '/api/v5/asset/convert/currencies');
    }

    public function getConvertCurrencyPair(string $fromCcy, string $toCcy): array
    {
        return $this->client->request('GET', '/api/v5/asset/convert/currency-pair', [
            'query' => ['fromCcy' => $fromCcy, 'toCcy' => $toCcy],
        ]);
    }

    public function getConvertHistory(?string $after = null, ?string $before = null, ?int $limit = null, ?string $tag = null): array
    {
        $query = array_filter([
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/asset/convert/history', ['query' => $query]);
    }

    public function getCurrencies(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/asset/currencies', $options);
    }

    public function getDepositAddress(string $ccy): array
    {
        return $this->client->request('GET', '/api/v5/asset/deposit-address', [
            'query' => ['ccy' => $ccy],
        ]);
    }

    public function getDepositHistory(?string $ccy = null, ?string $state = null, ?string $after = null, ?string $before = null, ?int $limit = null, ?string $txId = null, ?string $type = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'state' => $state,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'txId' => $txId,
            'type' => $type,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/asset/deposit-history', ['query' => $query]);
    }

    public function getExchangeList(): array
    {
        return $this->client->request('GET', '/api/v5/asset/exchange-list');
    }

    public function getLendingRateHistory(?string $ccy = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/asset/lending-rate-history', ['query' => $query]);
    }

    public function getLendingRateSummary(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/asset/lending-rate-summary', $options);
    }

    public function getMonthlyStatement(string $month): array
    {
        return $this->client->request('GET', '/api/v5/asset/monthly-statement', [
            'query' => ['month' => $month],
        ]);
    }

    public function getNonTradableAssets(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/asset/non-tradable-assets', $options);
    }

    public function getPurchaseRedempt(string $ccy, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/asset/purchase-redempt', ['query' => $query]);
    }

    public function getSavingBalance(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/asset/saving-balance', $options);
    }

    public function setLendingRate(string $ccy, string $rate): array
    {
        return $this->client->request('POST', '/api/v5/asset/set-lending-rate', [
            'json' => ['ccy' => $ccy, 'rate' => $rate],
        ]);
    }

    public function getTransferState(?string $transId = null, ?string $clientId = null, ?string $type = null): array
    {
        $query = array_filter([
            'transId' => $transId,
            'clientId' => $clientId,
            'type' => $type,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/asset/transfer-state', ['query' => $query]);
    }

    public function getWithdrawalHistory(?string $ccy = null, ?string $wdId = null, ?string $clientId = null, ?string $txId = null, ?string $type = null, ?string $state = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'wdId' => $wdId,
            'clientId' => $clientId,
            'txId' => $txId,
            'type' => $type,
            'state' => $state,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/asset/withdrawal-history', ['query' => $query]);
    }

    public function cancelWithdrawal(string $wdId): array
    {
        return $this->client->request('POST', '/api/v5/asset/cancel-withdrawal', [
            'json' => ['wdId' => $wdId],
        ]);
    }

    public function convertEstimateQuote(string $baseCcy, string $quoteCcy, string $side, string $rfqSz, string $rfqSzCcy, ?string $clQReqId = null, ?string $tag = null): array
    {
        $data = array_filter([
            'baseCcy' => $baseCcy,
            'quoteCcy' => $quoteCcy,
            'side' => $side,
            'rfqSz' => $rfqSz,
            'rfqSzCcy' => $rfqSzCcy,
            'clQReqId' => $clQReqId,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/asset/convert/estimate-quote', ['json' => $data]);
    }

    public function convertTrade(string $quoteId, ?string $baseCcy = null, ?string $quoteCcy = null, ?string $side = null, ?string $sz = null, ?string $szCcy = null, ?string $clTReqId = null, ?string $tag = null): array
    {
        $data = array_filter([
            'quoteId' => $quoteId,
            'baseCcy' => $baseCcy,
            'quoteCcy' => $quoteCcy,
            'side' => $side,
            'sz' => $sz,
            'szCcy' => $szCcy,
            'clTReqId' => $clTReqId,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/asset/convert/trade', ['json' => $data]);
    }

    public function requestMonthlyStatement(string $month): array
    {
        return $this->client->request('POST', '/api/v5/asset/monthly-statement', [
            'json' => ['month' => $month],
        ]);
    }

    public function subaccountTransfer(string $ccy, string $amt, string $from, string $to, string $subAcct, ?string $type = null, ?bool $loanTrans = null, ?string $clientId = null, ?bool $omitPosRisk = null): array
    {
        $data = array_filter([
            'ccy' => $ccy,
            'amt' => $amt,
            'from' => $from,
            'to' => $to,
            'subAcct' => $subAcct,
            'type' => $type,
            'loanTrans' => $loanTrans,
            'clientId' => $clientId,
            'omitPosRisk' => $omitPosRisk,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/asset/subaccount/transfer', ['json' => $data]);
    }

    public function transfer(string $ccy, string $amt, string $from, string $to, ?string $type = null, ?string $subAcct = null, ?string $instId = null, ?string $toInstId = null, ?bool $loanTrans = null, ?string $clientId = null, ?bool $omitPosRisk = null): array
    {
        $data = array_filter([
            'ccy' => $ccy,
            'amt' => $amt,
            'from' => $from,
            'to' => $to,
            'type' => $type,
            'subAcct' => $subAcct,
            'instId' => $instId,
            'toInstId' => $toInstId,
            'loanTrans' => $loanTrans,
            'clientId' => $clientId,
            'omitPosRisk' => $omitPosRisk,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/asset/transfer', ['json' => $data]);
    }

    public function withdrawal(string $ccy, string $amt, string $dest, string $toAddr, string $fee, ?string $chain = null, ?string $areaCode = null, ?string $clientId = null): array
    {
        $data = array_filter([
            'ccy' => $ccy,
            'amt' => $amt,
            'dest' => $dest,
            'toAddr' => $toAddr,
            'fee' => $fee,
            'chain' => $chain,
            'areaCode' => $areaCode,
            'clientId' => $clientId,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/asset/withdrawal', ['json' => $data]);
    }
}
