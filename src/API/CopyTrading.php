<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class CopyTrading extends BaseAPI
{
    public function getBatchLeverageInfo(string $mgnMode, string $uniqueCode, ?string $instId = null): array
    {
        $query = array_filter([
            'mgnMode' => $mgnMode,
            'uniqueCode' => $uniqueCode,
            'instId' => $instId,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/copytrading/batch-leverage-info', ['query' => $query]);
    }

    public function getConfig(): array
    {
        return $this->client->request('GET', '/api/v5/copytrading/config');
    }

    public function getCopySettings(string $uniqueCode): array
    {
        return $this->client->request('GET', '/api/v5/copytrading/copy-settings', [
            'query' => ['uniqueCode' => $uniqueCode],
        ]);
    }

    public function getCopyTraders(?string $instType = null, ?string $instId = null, ?string $uniqueCode = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'instId' => $instId,
            'uniqueCode' => $uniqueCode,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/copytrading/copy-traders', ['query' => $query]);
    }

    public function getCurrentSubpositions(string $uniqueCode, ?string $instId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'uniqueCode' => $uniqueCode,
            'instId' => $instId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/copytrading/current-subpositions', ['query' => $query]);
    }

    public function getInstruments(?string $instType = null): array
    {
        $options = [];
        if ($instType !== null) {
            $options['query'] = ['instType' => $instType];
        }
        return $this->client->request('GET', '/api/v5/copytrading/instruments', $options);
    }

    public function getLeadTraders(?string $instType = null): array
    {
        $options = [];
        if ($instType !== null) {
            $options['query'] = ['instType' => $instType];
        }
        return $this->client->request('GET', '/api/v5/copytrading/lead-traders', $options);
    }

    public function getMyLeadTraders(?string $instType = null): array
    {
        $options = [];
        if ($instType !== null) {
            $options['query'] = ['instType' => $instType];
        }
        return $this->client->request('GET', '/api/v5/copytrading/my-lead-traders', $options);
    }

    public function getPerformanceCurrentSubpositions(?string $instType = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/copytrading/performance-current-subpositions', ['query' => $query]);
    }

    public function getPerformanceSubpositionsHistory(?string $instType = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/copytrading/performance-subpositions-history', ['query' => $query]);
    }

    public function getProfitSharingDetails(?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/copytrading/profit-sharing-details', ['query' => $query]);
    }

    public function getPublicConfig(?string $instType = null): array
    {
        $options = [];
        if ($instType !== null) {
            $options['query'] = ['instType' => $instType];
        }
        return $this->client->request('GET', '/api/v5/copytrading/public-config', $options);
    }

    public function getPublicLeadTraders(?string $instType = null, ?string $sortType = null, ?string $state = null, ?string $minLeadDays = null, ?string $minAssets = null, ?string $maxAssets = null, ?string $minAum = null, ?string $maxAum = null, ?string $dataVer = null, ?int $page = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'sortType' => $sortType,
            'state' => $state,
            'minLeadDays' => $minLeadDays,
            'minAssets' => $minAssets,
            'maxAssets' => $maxAssets,
            'minAum' => $minAum,
            'maxAum' => $maxAum,
            'dataVer' => $dataVer,
            'page' => $page,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/copytrading/public-lead-traders', ['query' => $query]);
    }

    public function getPublicPreferenceCurrency(string $uniqueCode): array
    {
        return $this->client->request('GET', '/api/v5/copytrading/public-preference-currency', [
            'query' => ['uniqueCode' => $uniqueCode],
        ]);
    }

    public function getPublicStats(string $uniqueCode, ?string $lastDays = null): array
    {
        $query = array_filter([
            'uniqueCode' => $uniqueCode,
            'lastDays' => $lastDays,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/copytrading/public-stats', ['query' => $query]);
    }

    public function getPublicSubpositionsHistory(string $uniqueCode, ?string $instId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'uniqueCode' => $uniqueCode,
            'instId' => $instId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/copytrading/public-subpositions-history', ['query' => $query]);
    }

    public function getSubpositionsHistory(string $uniqueCode, ?string $instId = null, ?string $subPosId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'uniqueCode' => $uniqueCode,
            'instId' => $instId,
            'subPosId' => $subPosId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/copytrading/subpositions-history', ['query' => $query]);
    }

    public function getTotalProfitSharing(): array
    {
        return $this->client->request('GET', '/api/v5/copytrading/total-profit-sharing');
    }

    public function getUnrealizedProfitSharingDetails(): array
    {
        return $this->client->request('GET', '/api/v5/copytrading/unrealized-profit-sharing-details');
    }

    public function placeAlgoOrder(string $instId, string $algoOrdType, string $side, string $sz, ?string $posSide = null, ?string $reduceOnly = null, ?string $tpTriggerPx = null, ?string $tpOrdPx = null, ?string $slTriggerPx = null, ?string $slOrdPx = null, ?string $tpTriggerPxType = null, ?string $slTriggerPxType = null, ?string $tag = null): array
    {
        $data = array_filter([
            'instId' => $instId,
            'algoOrdType' => $algoOrdType,
            'side' => $side,
            'sz' => $sz,
            'posSide' => $posSide,
            'reduceOnly' => $reduceOnly,
            'tpTriggerPx' => $tpTriggerPx,
            'tpOrdPx' => $tpOrdPx,
            'slTriggerPx' => $slTriggerPx,
            'slOrdPx' => $slOrdPx,
            'tpTriggerPxType' => $tpTriggerPxType,
            'slTriggerPxType' => $slTriggerPxType,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/copytrading/algo-order', ['json' => $data]);
    }

    public function amendCopySettings(string $uniqueCode, string $instType, ?string $instId = null, ?string $copyMgnMode = null, ?string $copyInstIdType = null, ?string $instIdType = null, ?string $copyMode = null, ?string $copyTotalAmt = null, ?string $copyAmt = null, ?string $copyRatio = null, ?string $tpRatio = null, ?string $slRatio = null, ?string $slTotalAmt = null, ?array $subPosCloseType = null): array
    {
        $data = array_filter([
            'uniqueCode' => $uniqueCode,
            'instType' => $instType,
            'instId' => $instId,
            'copyMgnMode' => $copyMgnMode,
            'copyInstIdType' => $copyInstIdType,
            'instIdType' => $instIdType,
            'copyMode' => $copyMode,
            'copyTotalAmt' => $copyTotalAmt,
            'copyAmt' => $copyAmt,
            'copyRatio' => $copyRatio,
            'tpRatio' => $tpRatio,
            'slRatio' => $slRatio,
            'slTotalAmt' => $slTotalAmt,
            'subPosCloseType' => $subPosCloseType,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/copytrading/amend-copy-settings', ['json' => $data]);
    }

    public function amendProfitSharingRatio(string $profitSharingRatio): array
    {
        return $this->client->request('POST', '/api/v5/copytrading/amend-profit-sharing-ratio', [
            'json' => ['profitSharingRatio' => $profitSharingRatio],
        ]);
    }

    public function closeSubposition(string $subPosId): array
    {
        return $this->client->request('POST', '/api/v5/copytrading/close-subposition', [
            'json' => ['subPosId' => $subPosId],
        ]);
    }

    public function firstCopySettings(string $uniqueCode, string $instType, string $copyMgnMode, string $copyInstIdType, string $instId, string $copyMode, string $copyTotalAmt, ?string $copyAmt = null, ?string $copyRatio = null, ?string $tpRatio = null, ?string $slRatio = null, ?string $slTotalAmt = null): array
    {
        $data = array_filter([
            'uniqueCode' => $uniqueCode,
            'instType' => $instType,
            'copyMgnMode' => $copyMgnMode,
            'copyInstIdType' => $copyInstIdType,
            'instId' => $instId,
            'copyMode' => $copyMode,
            'copyTotalAmt' => $copyTotalAmt,
            'copyAmt' => $copyAmt,
            'copyRatio' => $copyRatio,
            'tpRatio' => $tpRatio,
            'slRatio' => $slRatio,
            'slTotalAmt' => $slTotalAmt,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/copytrading/first-copy-settings', ['json' => $data]);
    }

    public function setInstruments(string $instType, array $instId): array
    {
        return $this->client->request('POST', '/api/v5/copytrading/set-instruments', [
            'json' => [
                'instType' => $instType,
                'instId' => $instId,
            ],
        ]);
    }

    public function stopCopyTrading(string $uniqueCode, string $instType): array
    {
        return $this->client->request('POST', '/api/v5/copytrading/stop-copy-trading', [
            'json' => [
                'uniqueCode' => $uniqueCode,
                'instType' => $instType,
            ],
        ]);
    }
}
