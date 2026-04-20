<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Trade extends BaseAPI
{
    public function getAccountRateLimit(): array
    {
        return $this->client->request('GET', '/api/v5/trade/account-rate-limit');
    }

    public function getEasyConvertCurrencyList(): array
    {
        return $this->client->request('GET', '/api/v5/trade/easy-convert-currency-list');
    }

    public function getEasyConvertHistory(?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/easy-convert-history', ['query' => $query]);
    }

    public function getFills(?string $instType = null, ?string $uly = null, ?string $instId = null, ?string $ordId = null, ?string $after = null, ?string $before = null, ?string $begin = null, ?string $end = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'instId' => $instId,
            'ordId' => $ordId,
            'after' => $after,
            'before' => $before,
            'begin' => $begin,
            'end' => $end,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/fills', ['query' => $query]);
    }

    public function getFillsHistory(string $instType, ?string $uly = null, ?string $instId = null, ?string $ordId = null, ?string $after = null, ?string $before = null, ?string $begin = null, ?string $end = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'instId' => $instId,
            'ordId' => $ordId,
            'after' => $after,
            'before' => $before,
            'begin' => $begin,
            'end' => $end,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/fills-history', ['query' => $query]);
    }

    public function getOneClickRepayCurrencyList(?string $debtType = null): array
    {
        $options = [];
        if ($debtType !== null) {
            $options['query'] = ['debtType' => $debtType];
        }
        return $this->client->request('GET', '/api/v5/trade/one-click-repay-currency-list', $options);
    }

    public function getOneClickRepayCurrencyListV2(?string $debtType = null): array
    {
        $options = [];
        if ($debtType !== null) {
            $options['query'] = ['debtType' => $debtType];
        }
        return $this->client->request('GET', '/api/v5/trade/one-click-repay-currency-list-v2', $options);
    }

    public function getOneClickRepayHistory(?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/one-click-repay-history', ['query' => $query]);
    }

    public function getOneClickRepayHistoryV2(?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/one-click-repay-history-v2', ['query' => $query]);
    }

    public function getOrder(string $instId, ?string $ordId = null, ?string $clOrdId = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'ordId' => $ordId,
            'clOrdId' => $clOrdId,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/order', ['query' => $query]);
    }

    public function getOrderAlgo(string $algoId, ?string $algoClOrdId = null): array
    {
        $query = array_filter([
            'algoId' => $algoId,
            'algoClOrdId' => $algoClOrdId,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/order-algo', ['query' => $query]);
    }

    public function getOrdersAlgoHistory(string $algoOrdType, ?string $state = null, ?string $algoId = null, ?string $instType = null, ?string $instId = null, ?string $after = null, ?string $before = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'algoOrdType' => $algoOrdType,
            'state' => $state,
            'algoId' => $algoId,
            'instType' => $instType,
            'instId' => $instId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/orders-algo-history', ['query' => $query]);
    }

    public function getOrdersAlgoPending(string $algoOrdType, ?string $algoId = null, ?string $instType = null, ?string $instId = null, ?string $after = null, ?string $before = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'algoOrdType' => $algoOrdType,
            'algoId' => $algoId,
            'instType' => $instType,
            'instId' => $instId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/orders-algo-pending', ['query' => $query]);
    }

    public function getOrdersHistory(string $instType, ?string $uly = null, ?string $instId = null, ?string $ordType = null, ?string $state = null, ?string $after = null, ?string $before = null, ?string $begin = null, ?string $end = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'instId' => $instId,
            'ordType' => $ordType,
            'state' => $state,
            'after' => $after,
            'before' => $before,
            'begin' => $begin,
            'end' => $end,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/orders-history', ['query' => $query]);
    }

    public function getOrdersHistoryArchive(string $instType, ?string $uly = null, ?string $instId = null, ?string $ordType = null, ?string $state = null, ?string $after = null, ?string $before = null, ?string $begin = null, ?string $end = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'instId' => $instId,
            'ordType' => $ordType,
            'state' => $state,
            'after' => $after,
            'before' => $before,
            'begin' => $begin,
            'end' => $end,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/orders-history-archive', ['query' => $query]);
    }

    public function getOrdersPending(?string $instType = null, ?string $uly = null, ?string $instId = null, ?string $ordType = null, ?string $state = null, ?string $after = null, ?string $before = null, ?int $limit = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'uly' => $uly,
            'instId' => $instId,
            'ordType' => $ordType,
            'state' => $state,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/trade/orders-pending', ['query' => $query]);
    }

    public function amendAlgos(string $instId, ?string $algoId = null, ?string $algoClOrdId = null, ?string $cxlOnFail = null, ?string $reqId = null, ?string $newSz = null, ?string $newTpTriggerPx = null, ?string $newTpOrdPx = null, ?string $newSlTriggerPx = null, ?string $newSlOrdPx = null, ?string $newTpTriggerPxType = null, ?string $newSlTriggerPxType = null): array
    {
        $data = array_filter([
            'instId' => $instId,
            'algoId' => $algoId,
            'algoClOrdId' => $algoClOrdId,
            'cxlOnFail' => $cxlOnFail,
            'reqId' => $reqId,
            'newSz' => $newSz,
            'newTpTriggerPx' => $newTpTriggerPx,
            'newTpOrdPx' => $newTpOrdPx,
            'newSlTriggerPx' => $newSlTriggerPx,
            'newSlOrdPx' => $newSlOrdPx,
            'newTpTriggerPxType' => $newTpTriggerPxType,
            'newSlTriggerPxType' => $newSlTriggerPxType,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/trade/amend-algos', ['json' => $data]);
    }

    public function amendBatchOrders(array $orders): array
    {
        return $this->client->request('POST', '/api/v5/trade/amend-batch-orders', [
            'json' => $orders,
        ]);
    }

    public function amendOrder(string $instId, ?string $cxlOnFail = null, ?string $ordId = null, ?string $clOrdId = null, ?string $reqId = null, ?string $newSz = null, ?string $newPx = null, ?string $newTpTriggerPx = null, ?string $newTpOrdPx = null, ?string $newSlTriggerPx = null, ?string $newSlOrdPx = null, ?string $newTpTriggerPxType = null, ?string $newSlTriggerPxType = null): array
    {
        $data = array_filter([
            'instId' => $instId,
            'cxlOnFail' => $cxlOnFail,
            'ordId' => $ordId,
            'clOrdId' => $clOrdId,
            'reqId' => $reqId,
            'newSz' => $newSz,
            'newPx' => $newPx,
            'newTpTriggerPx' => $newTpTriggerPx,
            'newTpOrdPx' => $newTpOrdPx,
            'newSlTriggerPx' => $newSlTriggerPx,
            'newSlOrdPx' => $newSlOrdPx,
            'newTpTriggerPxType' => $newTpTriggerPxType,
            'newSlTriggerPxType' => $newSlTriggerPxType,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/trade/amend-order', ['json' => $data]);
    }

    public function batchOrders(array $orders): array
    {
        return $this->client->request('POST', '/api/v5/trade/batch-orders', [
            'json' => $orders,
        ]);
    }

    public function cancelAlgos(array $algos): array
    {
        return $this->client->request('POST', '/api/v5/trade/cancel-algos', [
            'json' => $algos,
        ]);
    }

    public function cancelAllAfter(string $timeOut, ?string $tag = null): array
    {
        $data = array_filter([
            'timeOut' => $timeOut,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/trade/cancel-all-after', ['json' => $data]);
    }

    public function cancelBatchOrders(array $orders): array
    {
        return $this->client->request('POST', '/api/v5/trade/cancel-batch-orders', [
            'json' => $orders,
        ]);
    }

    public function cancelOrder(string $instId, ?string $ordId = null, ?string $clOrdId = null): array
    {
        $data = array_filter([
            'instId' => $instId,
            'ordId' => $ordId,
            'clOrdId' => $clOrdId,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/trade/cancel-order', ['json' => $data]);
    }

    public function closePosition(string $instId, string $mgnMode, ?string $posSide = null, ?string $ccy = null, ?bool $autoCxl = null, ?string $clOrdId = null, ?string $tag = null): array
    {
        $data = array_filter([
            'instId' => $instId,
            'mgnMode' => $mgnMode,
            'posSide' => $posSide,
            'ccy' => $ccy,
            'autoCxl' => $autoCxl,
            'clOrdId' => $clOrdId,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/trade/close-position', ['json' => $data]);
    }

    public function easyConvert(array $fromCcy, string $toCcy): array
    {
        return $this->client->request('POST', '/api/v5/trade/easy-convert', [
            'json' => [
                'fromCcy' => $fromCcy,
                'toCcy' => $toCcy,
            ],
        ]);
    }

    public function massCancel(?string $instType = null, ?string $instFamily = null): array
    {
        $data = array_filter([
            'instType' => $instType,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/trade/mass-cancel', ['json' => $data]);
    }

    public function oneClickRepay(array $debtCcy, string $repayCcy): array
    {
        return $this->client->request('POST', '/api/v5/trade/one-click-repay', [
            'json' => [
                'debtCcy' => $debtCcy,
                'repayCcy' => $repayCcy,
            ],
        ]);
    }

    public function oneClickRepayV2(array $debtCcy, string $repayCcy): array
    {
        return $this->client->request('POST', '/api/v5/trade/one-click-repay-v2', [
            'json' => [
                'debtCcy' => $debtCcy,
                'repayCcy' => $repayCcy,
            ],
        ]);
    }

    public function placeOrder(string $instId, string $tdMode, string $side, string $ordType, string $sz, ?string $ccy = null, ?string $clOrdId = null, ?string $tag = null, ?string $posSide = null, ?string $px = null, ?bool $reduceOnly = null, ?string $tgtCcy = null, ?bool $banAmend = null, ?string $tpTriggerPx = null, ?string $tpOrdPx = null, ?string $slTriggerPx = null, ?string $slOrdPx = null, ?string $tpTriggerPxType = null, ?string $slTriggerPxType = null, ?string $quickMgnType = null, ?string $stpId = null, ?string $stpMode = null, ?array $attachAlgoOrds = null): array
    {
        $data = array_filter([
            'instId' => $instId,
            'tdMode' => $tdMode,
            'side' => $side,
            'ordType' => $ordType,
            'sz' => $sz,
            'ccy' => $ccy,
            'clOrdId' => $clOrdId,
            'tag' => $tag,
            'posSide' => $posSide,
            'px' => $px,
            'reduceOnly' => $reduceOnly,
            'tgtCcy' => $tgtCcy,
            'banAmend' => $banAmend,
            'tpTriggerPx' => $tpTriggerPx,
            'tpOrdPx' => $tpOrdPx,
            'slTriggerPx' => $slTriggerPx,
            'slOrdPx' => $slOrdPx,
            'tpTriggerPxType' => $tpTriggerPxType,
            'slTriggerPxType' => $slTriggerPxType,
            'quickMgnType' => $quickMgnType,
            'stpId' => $stpId,
            'stpMode' => $stpMode,
            'attachAlgoOrds' => $attachAlgoOrds,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/trade/order', ['json' => $data]);
    }

    public function placeAlgoOrder(string $instId, string $tdMode, string $side, string $ordType, string $sz, ?string $ccy = null, ?string $posSide = null, ?bool $reduceOnly = null, ?string $tpTriggerPx = null, ?string $tpOrdPx = null, ?string $slTriggerPx = null, ?string $slOrdPx = null, ?string $tpTriggerPxType = null, ?string $slTriggerPxType = null, ?string $triggerPx = null, ?string $orderPx = null, ?string $triggerPxType = null, ?string $pxVar = null, ?string $pxSpread = null, ?string $szLimit = null, ?string $pxLimit = null, ?string $timeInterval = null, ?string $tgtCcy = null, ?string $algoClOrdId = null, ?string $tag = null, ?string $quickMgnType = null, ?string $closeFraction = null): array
    {
        $data = array_filter([
            'instId' => $instId,
            'tdMode' => $tdMode,
            'side' => $side,
            'ordType' => $ordType,
            'sz' => $sz,
            'ccy' => $ccy,
            'posSide' => $posSide,
            'reduceOnly' => $reduceOnly,
            'tpTriggerPx' => $tpTriggerPx,
            'tpOrdPx' => $tpOrdPx,
            'slTriggerPx' => $slTriggerPx,
            'slOrdPx' => $slOrdPx,
            'tpTriggerPxType' => $tpTriggerPxType,
            'slTriggerPxType' => $slTriggerPxType,
            'triggerPx' => $triggerPx,
            'orderPx' => $orderPx,
            'triggerPxType' => $triggerPxType,
            'pxVar' => $pxVar,
            'pxSpread' => $pxSpread,
            'szLimit' => $szLimit,
            'pxLimit' => $pxLimit,
            'timeInterval' => $timeInterval,
            'tgtCcy' => $tgtCcy,
            'algoClOrdId' => $algoClOrdId,
            'tag' => $tag,
            'quickMgnType' => $quickMgnType,
            'closeFraction' => $closeFraction,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/trade/order-algo', ['json' => $data]);
    }

    public function orderPrecheck(string $instId, string $tdMode, string $side, string $ordType, string $sz, ?string $tgtCcy = null, ?string $px = null, ?string $tag = null): array
    {
        $data = array_filter([
            'instId' => $instId,
            'tdMode' => $tdMode,
            'side' => $side,
            'ordType' => $ordType,
            'sz' => $sz,
            'tgtCcy' => $tgtCcy,
            'px' => $px,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/trade/order-precheck', ['json' => $data]);
    }
}
