<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class TradingBot extends BaseAPI
{
    public function getGridAiParam(string $algoOrdType, string $instId, ?string $direction = null, ?string $duration = null): array
    {
        $query = array_filter([
            'algoOrdType' => $algoOrdType,
            'instId' => $instId,
            'direction' => $direction,
            'duration' => $duration,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/grid/ai-param', ['query' => $query]);
    }

    public function getGridQuantity(string $algoOrdType, string $instId, string $maxPx, string $minPx, ?string $gridNum = null, ?string $runType = null, ?string $direction = null, ?string $lever = null, ?string $basePos = null, ?string $investmentData = null, ?string $gridRatio = null): array
    {
        $query = array_filter([
            'algoOrdType' => $algoOrdType,
            'instId' => $instId,
            'maxPx' => $maxPx,
            'minPx' => $minPx,
            'gridNum' => $gridNum,
            'runType' => $runType,
            'direction' => $direction,
            'lever' => $lever,
            'basePos' => $basePos,
            'investmentData' => $investmentData,
            'gridRatio' => $gridRatio,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/grid/grid-quantity', ['query' => $query]);
    }

    public function getGridOrdersAlgoDetails(string $algoOrdType, string $algoId): array
    {
        return $this->client->request('GET', '/api/v5/tradingBot/grid/orders-algo-details', [
            'query' => ['algoOrdType' => $algoOrdType, 'algoId' => $algoId],
        ]);
    }

    public function getGridOrdersAlgoHistory(string $algoOrdType, ?string $algoId = null, ?string $instId = null, ?string $instType = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoOrdType' => $algoOrdType,
            'algoId' => $algoId,
            'instId' => $instId,
            'instType' => $instType,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/grid/orders-algo-history', ['query' => $query]);
    }

    public function getGridOrdersAlgoPending(string $algoOrdType, ?string $algoId = null, ?string $instId = null, ?string $instType = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoOrdType' => $algoOrdType,
            'algoId' => $algoId,
            'instId' => $instId,
            'instType' => $instType,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/grid/orders-algo-pending', ['query' => $query]);
    }

    public function getGridPositions(string $algoOrdType, string $algoId): array
    {
        return $this->client->request('GET', '/api/v5/tradingBot/grid/positions', [
            'query' => ['algoOrdType' => $algoOrdType, 'algoId' => $algoId],
        ]);
    }

    public function getGridSubOrders(string $algoOrdType, string $algoId, string $type, ?string $groupId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoOrdType' => $algoOrdType,
            'algoId' => $algoId,
            'type' => $type,
            'groupId' => $groupId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/grid/sub-orders', ['query' => $query]);
    }

    public function getPublicRsiBackTesting(string $instId, string $timeframe, string $thold, string $timePeriod): array
    {
        return $this->client->request('GET', '/api/v5/tradingBot/public/rsi-back-testing', [
            'query' => [
                'instId' => $instId,
                'timeframe' => $timeframe,
                'thold' => $thold,
                'timePeriod' => $timePeriod,
            ],
        ]);
    }

    public function getRecurringOrdersAlgoDetails(string $algoId): array
    {
        return $this->client->request('GET', '/api/v5/tradingBot/recurring/orders-algo-details', [
            'query' => ['algoId' => $algoId],
        ]);
    }

    public function getRecurringOrdersAlgoHistory(?string $algoId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoId' => $algoId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/recurring/orders-algo-history', ['query' => $query]);
    }

    public function getRecurringOrdersAlgoPending(?string $algoId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoId' => $algoId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/recurring/orders-algo-pending', ['query' => $query]);
    }

    public function getRecurringSubOrders(string $algoId, ?string $ordId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoId' => $algoId,
            'ordId' => $ordId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/recurring/sub-orders', ['query' => $query]);
    }

    public function getSignalEventHistory(string $algoId, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoId' => $algoId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/signal/event-history', ['query' => $query]);
    }

    public function getSignalOrdersAlgoDetails(string $algoOrdType, string $algoId): array
    {
        return $this->client->request('GET', '/api/v5/tradingBot/signal/orders-algo-details', [
            'query' => ['algoOrdType' => $algoOrdType, 'algoId' => $algoId],
        ]);
    }

    public function getSignalOrdersAlgoHistory(string $algoOrdType, ?string $algoId = null, ?string $instId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoOrdType' => $algoOrdType,
            'algoId' => $algoId,
            'instId' => $instId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/signal/orders-algo-history', ['query' => $query]);
    }

    public function getSignalOrdersAlgoPending(string $algoOrdType, ?string $algoId = null, ?string $instId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoOrdType' => $algoOrdType,
            'algoId' => $algoId,
            'instId' => $instId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/signal/orders-algo-pending', ['query' => $query]);
    }

    public function getSignalPositions(string $algoOrdType, string $algoId): array
    {
        return $this->client->request('GET', '/api/v5/tradingBot/signal/positions', [
            'query' => ['algoOrdType' => $algoOrdType, 'algoId' => $algoId],
        ]);
    }

    public function getSignalPositionsHistory(?string $algoId = null, ?string $instId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoId' => $algoId,
            'instId' => $instId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/signal/positions-history', ['query' => $query]);
    }

    public function getSignals(string $signalSourceType, ?string $signalChanId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'signalSourceType' => $signalSourceType,
            'signalChanId' => $signalChanId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/signal/signals', ['query' => $query]);
    }

    public function getSignalSubOrders(string $algoId, string $type, ?string $clOrdId = null, ?string $state = null, ?string $after = null, ?string $before = null, ?string $begin = null, ?string $end = null, ?int $limit = null): array
    {
        $query = array_filter([
            'algoId' => $algoId,
            'type' => $type,
            'clOrdId' => $clOrdId,
            'state' => $state,
            'after' => $after,
            'before' => $before,
            'begin' => $begin,
            'end' => $end,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/tradingBot/signal/sub-orders', ['query' => $query]);
    }

    public function adjustGridInvestment(string $algoId, string $type, string $amt): array
    {
        return $this->client->request('POST', '/api/v5/tradingBot/grid/adjust-investment', [
            'json' => [
                'algoId' => $algoId,
                'type' => $type,
                'amt' => $amt,
            ],
        ]);
    }

    public function amendGridAlgoBasicParam(string $algoId, string $instId, ?string $slTriggerPx = null, ?string $tpTriggerPx = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'instId' => $instId,
            'slTriggerPx' => $slTriggerPx,
            'tpTriggerPx' => $tpTriggerPx,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/grid/amend-algo-basic-param', ['json' => $data]);
    }

    public function amendGridOrderAlgo(string $algoId, string $instId, ?string $slTriggerPx = null, ?string $tpTriggerPx = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'instId' => $instId,
            'slTriggerPx' => $slTriggerPx,
            'tpTriggerPx' => $tpTriggerPx,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/grid/amend-order-algo', ['json' => $data]);
    }

    public function cancelGridCloseOrder(string $algoId, string $ordId): array
    {
        return $this->client->request('POST', '/api/v5/tradingBot/grid/cancel-close-order', [
            'json' => [
                'algoId' => $algoId,
                'ordId' => $ordId,
            ],
        ]);
    }

    public function closeGridPosition(string $algoId, string $mgnMode, ?string $instId = null, ?string $algoOrdType = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'mgnMode' => $mgnMode,
            'instId' => $instId,
            'algoOrdType' => $algoOrdType,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/grid/close-position', ['json' => $data]);
    }

    public function computeGridMarginBalance(string $algoId, string $type, ?string $amt = null, ?string $percent = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'type' => $type,
            'amt' => $amt,
            'percent' => $percent,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/grid/compute-margin-balance', ['json' => $data]);
    }

    public function adjustGridMarginBalance(string $algoId, string $type, ?string $amt = null, ?string $percent = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'type' => $type,
            'amt' => $amt,
            'percent' => $percent,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/grid/margin-balance', ['json' => $data]);
    }

    public function getGridMinInvestment(string $algoOrdType, string $instId, ?string $maxPx = null, ?string $minPx = null, ?string $gridNum = null, ?string $runType = null, ?string $direction = null, ?string $lever = null, ?string $basePos = null, ?string $investmentData = null, ?string $gridRatio = null): array
    {
        $data = array_filter([
            'algoOrdType' => $algoOrdType,
            'instId' => $instId,
            'maxPx' => $maxPx,
            'minPx' => $minPx,
            'gridNum' => $gridNum,
            'runType' => $runType,
            'direction' => $direction,
            'lever' => $lever,
            'basePos' => $basePos,
            'investmentData' => $investmentData,
            'gridRatio' => $gridRatio,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/grid/min-investment', ['json' => $data]);
    }

    public function placeGridOrderAlgo(string $instId, string $algoOrdType, ?string $maxPx = null, ?string $minPx = null, ?string $gridNum = null, ?string $runType = null, ?string $tpTriggerPx = null, ?string $slTriggerPx = null, ?string $quoteSz = null, ?string $baseSz = null, ?string $sz = null, ?string $direction = null, ?string $lever = null, ?string $basePos = null, ?string $tag = null): array
    {
        $data = array_filter([
            'instId' => $instId,
            'algoOrdType' => $algoOrdType,
            'maxPx' => $maxPx,
            'minPx' => $minPx,
            'gridNum' => $gridNum,
            'runType' => $runType,
            'tpTriggerPx' => $tpTriggerPx,
            'slTriggerPx' => $slTriggerPx,
            'quoteSz' => $quoteSz,
            'baseSz' => $baseSz,
            'sz' => $sz,
            'direction' => $direction,
            'lever' => $lever,
            'basePos' => $basePos,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/grid/order-algo', ['json' => $data]);
    }

    public function instantTriggerGridOrder(string $algoId): array
    {
        return $this->client->request('POST', '/api/v5/tradingBot/grid/order-instant-trigger', [
            'json' => ['algoId' => $algoId],
        ]);
    }

    public function stopGridOrderAlgo(array $orders): array
    {
        return $this->client->request('POST', '/api/v5/tradingBot/grid/stop-order-algo', [
            'json' => $orders,
        ]);
    }

    public function withdrawGridIncome(string $algoId): array
    {
        return $this->client->request('POST', '/api/v5/tradingBot/grid/withdraw-income', [
            'json' => ['algoId' => $algoId],
        ]);
    }

    public function amendRecurringOrderAlgo(string $algoId, string $instId, ?string $recurringList = null, ?string $tag = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'instId' => $instId,
            'recurringList' => $recurringList,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/recurring/amend-order-algo', ['json' => $data]);
    }

    public function placeRecurringOrderAlgo(string $stgyName, array $recurringList, string $period, string $recurringDay, string $recurringTime, string $timeZone, string $amt, string $investmentCcy, string $tdMode, ?string $algoClOrdId = null, ?string $tag = null): array
    {
        $data = array_filter([
            'stgyName' => $stgyName,
            'recurringList' => $recurringList,
            'period' => $period,
            'recurringDay' => $recurringDay,
            'recurringTime' => $recurringTime,
            'timeZone' => $timeZone,
            'amt' => $amt,
            'investmentCcy' => $investmentCcy,
            'tdMode' => $tdMode,
            'algoClOrdId' => $algoClOrdId,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/recurring/order-algo', ['json' => $data]);
    }

    public function stopRecurringOrderAlgo(array $orders): array
    {
        return $this->client->request('POST', '/api/v5/tradingBot/recurring/stop-order-algo', [
            'json' => $orders,
        ]);
    }

    public function amendSignalTPSL(string $algoId, ?string $tpTriggerPx = null, ?string $slTriggerPx = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'tpTriggerPx' => $tpTriggerPx,
            'slTriggerPx' => $slTriggerPx,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/signal/amendTPSL', ['json' => $data]);
    }

    public function cancelSignalSubOrder(string $algoId, string $signalOrdType, ?string $clOrdId = null, ?string $signalChanId = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'signalOrdType' => $signalOrdType,
            'clOrdId' => $clOrdId,
            'signalChanId' => $signalChanId,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/signal/cancel-sub-order', ['json' => $data]);
    }

    public function closeSignalPosition(string $algoId, string $mgnMode, ?string $instId = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'mgnMode' => $mgnMode,
            'instId' => $instId,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/signal/close-position', ['json' => $data]);
    }

    public function createSignal(string $signalChanName, string $signalSourceType, ?string $signalChanDesc = null, ?string $signalChanToken = null): array
    {
        $data = array_filter([
            'signalChanName' => $signalChanName,
            'signalSourceType' => $signalSourceType,
            'signalChanDesc' => $signalChanDesc,
            'signalChanToken' => $signalChanToken,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/signal/create-signal', ['json' => $data]);
    }

    public function adjustSignalMarginBalance(string $algoId, string $type, ?string $amt = null, ?string $allowReinvest = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'type' => $type,
            'amt' => $amt,
            'allowReinvest' => $allowReinvest,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/signal/margin-balance', ['json' => $data]);
    }

    public function placeSignalOrderAlgo(string $signalChanId, string $lever, string $investAmt, string $subOrdType, ?string $includeAll = null, ?string $instIds = null, ?string $ratio = null, ?string $entrySettingParam = null, ?string $exitSettingParam = null, ?string $tpTriggerPx = null, ?string $slTriggerPx = null, ?string $algoClOrdId = null, ?string $tag = null): array
    {
        $data = array_filter([
            'signalChanId' => $signalChanId,
            'lever' => $lever,
            'investAmt' => $investAmt,
            'subOrdType' => $subOrdType,
            'includeAll' => $includeAll,
            'instIds' => $instIds,
            'ratio' => $ratio,
            'entrySettingParam' => $entrySettingParam,
            'exitSettingParam' => $exitSettingParam,
            'tpTriggerPx' => $tpTriggerPx,
            'slTriggerPx' => $slTriggerPx,
            'algoClOrdId' => $algoClOrdId,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/signal/order-algo', ['json' => $data]);
    }

    public function setSignalInstruments(string $algoId, array $instIds): array
    {
        return $this->client->request('POST', '/api/v5/tradingBot/signal/set-instruments', [
            'json' => [
                'algoId' => $algoId,
                'instIds' => $instIds,
            ],
        ]);
    }

    public function stopSignalOrderAlgo(array $orders): array
    {
        return $this->client->request('POST', '/api/v5/tradingBot/signal/stop-order-algo', [
            'json' => $orders,
        ]);
    }

    public function placeSignalSubOrder(string $algoId, string $instId, string $side, string $ordType, string $sz, ?string $px = null, ?string $clOrdId = null, ?string $tag = null, ?string $reduceOnly = null, ?string $tgtCcy = null): array
    {
        $data = array_filter([
            'algoId' => $algoId,
            'instId' => $instId,
            'side' => $side,
            'ordType' => $ordType,
            'sz' => $sz,
            'px' => $px,
            'clOrdId' => $clOrdId,
            'tag' => $tag,
            'reduceOnly' => $reduceOnly,
            'tgtCcy' => $tgtCcy,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/tradingBot/signal/sub-order', ['json' => $data]);
    }
}
