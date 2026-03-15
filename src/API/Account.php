<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Account extends BaseAPI
{
    public function getAccountLevel(): array
    {
        return $this->client->request('GET', '/api/v5/account/account-level');
    }

    public function getBalance(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/account/balance', $options);
    }

    public function getBills(?string $instType = null, ?string $ccy = null, ?string $mgnMode = null, ?string $ctType = null, ?string $type = null, ?string $subType = null, ?string $after = null, ?string $before = null, ?int $limit = null, ?string $begin = null, ?string $end = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'ccy' => $ccy,
            'mgnMode' => $mgnMode,
            'ctType' => $ctType,
            'type' => $type,
            'subType' => $subType,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'begin' => $begin,
            'end' => $end,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/bills', ['query' => $query]);
    }

    public function getBillsHistoryArchive(?string $instType = null, ?string $ccy = null, ?string $mgnMode = null, ?string $ctType = null, ?string $type = null, ?string $subType = null, ?string $after = null, ?string $before = null, ?int $limit = null, ?string $begin = null, ?string $end = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'ccy' => $ccy,
            'mgnMode' => $mgnMode,
            'ctType' => $ctType,
            'type' => $type,
            'subType' => $subType,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'begin' => $begin,
            'end' => $end,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/bills-history-archive', ['query' => $query]);
    }

    public function getConfig(): array
    {
        return $this->client->request('GET', '/api/v5/account/config');
    }

    public function getFixedLoanBorrowOrder(?string $ordId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ordId' => $ordId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/fixed-loan/borrow-order', ['query' => $query]);
    }

    public function getFixedLoanBorrowingLimit(?string $type = null, ?string $ccy = null): array
    {
        $query = array_filter([
            'type' => $type,
            'ccy' => $ccy,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/fixed-loan/borrowing-limit', ['query' => $query]);
    }

    public function getFixedLoanBorrowingOrdersList(?string $ordId = null, ?string $state = null, ?string $ccy = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ordId' => $ordId,
            'state' => $state,
            'ccy' => $ccy,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/fixed-loan/borrowing-orders-list', ['query' => $query]);
    }

    public function getFixedLoanLendingOrdersList(?string $ordId = null, ?string $state = null, ?string $ccy = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ordId' => $ordId,
            'state' => $state,
            'ccy' => $ccy,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/fixed-loan/lending-orders-list', ['query' => $query]);
    }

    public function getFixedLoanLendingSubOrders(string $ordId, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ordId' => $ordId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/fixed-loan/lending-sub-orders', ['query' => $query]);
    }

    public function getGreeks(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/account/greeks', $options);
    }

    public function getInterestAccrued(?string $instId = null, ?string $ccy = null, ?string $mgnMode = null, ?string $after = null, ?string $before = null, ?int $limit = null, ?string $type = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'ccy' => $ccy,
            'mgnMode' => $mgnMode,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
            'type' => $type,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/interest-accrued', ['query' => $query]);
    }

    public function getInterestLimits(?string $type = null, ?string $ccy = null): array
    {
        $query = array_filter([
            'type' => $type,
            'ccy' => $ccy,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/interest-limits', ['query' => $query]);
    }

    public function getInterestRate(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/account/interest-rate', $options);
    }

    public function getLeverageInfo(string $instId, string $mgnMode): array
    {
        return $this->client->request('GET', '/api/v5/account/leverage-info', [
            'query' => ['instId' => $instId, 'mgnMode' => $mgnMode],
        ]);
    }

    public function getMaxAvailSize(string $instId, string $tdMode, ?string $ccy = null, ?string $reduceOnly = null, ?string $unSpotOffset = null, ?string $quickMgnType = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'tdMode' => $tdMode,
            'ccy' => $ccy,
            'reduceOnly' => $reduceOnly,
            'unSpotOffset' => $unSpotOffset,
            'quickMgnType' => $quickMgnType,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/max-avail-size', ['query' => $query]);
    }

    public function getMaxLoan(string $instId, string $mgnMode, ?string $mgnCcy = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'mgnMode' => $mgnMode,
            'mgnCcy' => $mgnCcy,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/max-loan', ['query' => $query]);
    }

    public function getMaxSize(string $instId, string $tdMode, ?string $ccy = null, ?string $px = null, ?string $leverage = null, ?string $unSpotOffset = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'tdMode' => $tdMode,
            'ccy' => $ccy,
            'px' => $px,
            'leverage' => $leverage,
            'unSpotOffset' => $unSpotOffset,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/max-size', ['query' => $query]);
    }

    public function getMaxWithdrawal(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/account/max-withdrawal', $options);
    }

    public function getMmpConfig(): array
    {
        return $this->client->request('GET', '/api/v5/account/mmp-config');
    }

    public function getMmpState(): array
    {
        return $this->client->request('GET', '/api/v5/account/mmp-state');
    }

    public function getMmpStateV2(): array
    {
        return $this->client->request('GET', '/api/v5/account/mmp-state-v2');
    }

    public function getPositions(?string $instType = null, ?string $instId = null, ?string $posId = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'instId' => $instId,
            'posId' => $posId,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/positions', ['query' => $query]);
    }

    public function getPositionsHistory(?string $instType = null, ?string $instId = null, ?string $mgnMode = null, ?string $type = null, ?string $posId = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'instId' => $instId,
            'mgnMode' => $mgnMode,
            'type' => $type,
            'posId' => $posId,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/positions-history', ['query' => $query]);
    }

    public function getQuickMarginBorrowRepayHistory(?string $instId = null, ?string $ccy = null, ?string $side = null, ?string $after = null, ?string $before = null, ?string $begin = null, ?string $end = null, ?int $limit = null): array
    {
        $query = array_filter([
            'instId' => $instId,
            'ccy' => $ccy,
            'side' => $side,
            'after' => $after,
            'before' => $before,
            'begin' => $begin,
            'end' => $end,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/quick-margin-borrow-repay-history', ['query' => $query]);
    }

    public function getRiskState(): array
    {
        return $this->client->request('GET', '/api/v5/account/risk-state');
    }

    public function getAutoEarn(): array
    {
        return $this->client->request('GET', '/api/v5/account/set-auto-earn');
    }

    public function getSimulatedMargin(?string $instType = null, ?bool $inclRealPos = null, ?array $spotOffsetType = null, ?array $simPos = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'inclRealPos' => $inclRealPos,
            'spotOffsetType' => $spotOffsetType,
            'simPos' => $simPos,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/simulated_margin', ['query' => $query]);
    }

    public function getSpotBorrowRepayHistory(?string $ccy = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/spot-borrow-repay-history', ['query' => $query]);
    }

    public function getSpotManualBorrowRepayHistory(?string $ccy = null, ?string $type = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'type' => $type,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/spot-manual-borrow-repay-history', ['query' => $query]);
    }

    public function getTradeFee(string $instType, ?string $instId = null, ?string $uly = null, ?string $instFamily = null): array
    {
        $query = array_filter([
            'instType' => $instType,
            'instId' => $instId,
            'uly' => $uly,
            'instFamily' => $instFamily,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/account/trade-fee', ['query' => $query]);
    }

    public function getTradingConfig(): array
    {
        return $this->client->request('GET', '/api/v5/account/trading-config');
    }

    public function switchAccountLevelPreset(string $acctLv): array
    {
        return $this->client->request('POST', '/api/v5/account/account-level-switch-preset', [
            'json' => ['acctLv' => $acctLv],
        ]);
    }

    public function activateOption(): array
    {
        return $this->client->request('POST', '/api/v5/account/activate-option');
    }

    public function requestBillsHistoryArchive(string $year, ?string $quarter = null, ?string $month = null): array
    {
        $data = array_filter([
            'year' => $year,
            'quarter' => $quarter,
            'month' => $month,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/account/bills-history-archive', ['json' => $data]);
    }

    public function setMmp(string $instFamily, string $timeInterval, string $frozenInterval, string $qtyLimit): array
    {
        return $this->client->request('POST', '/api/v5/account/mmp', [
            'json' => [
                'instFamily' => $instFamily,
                'timeInterval' => $timeInterval,
                'frozenInterval' => $frozenInterval,
                'qtyLimit' => $qtyLimit,
            ],
        ]);
    }

    public function resetMmp(string $instFamily): array
    {
        return $this->client->request('POST', '/api/v5/account/mmp-reset', [
            'json' => ['instFamily' => $instFamily],
        ]);
    }

    public function movePositions(array $positions, string $type): array
    {
        return $this->client->request('POST', '/api/v5/account/move-positions', [
            'json' => [
                'positions' => $positions,
                'type' => $type,
            ],
        ]);
    }

    public function positionBuilder(?array $inclRealPosAndEq = null, ?array $simPos = null, ?string $pos = null, ?string $instType = null, ?string $spotOffsetType = null): array
    {
        $data = array_filter([
            'inclRealPosAndEq' => $inclRealPosAndEq,
            'simPos' => $simPos,
            'pos' => $pos,
            'instType' => $instType,
            'spotOffsetType' => $spotOffsetType,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/account/position-builder', ['json' => $data]);
    }

    public function positionBuilderGraph(?array $inclRealPosAndEq = null, ?array $simPos = null, ?string $pos = null, ?string $instType = null, ?string $spotOffsetType = null): array
    {
        $data = array_filter([
            'inclRealPosAndEq' => $inclRealPosAndEq,
            'simPos' => $simPos,
            'pos' => $pos,
            'instType' => $instType,
            'spotOffsetType' => $spotOffsetType,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/account/position-builder-graph', ['json' => $data]);
    }

    public function increaseDecreaseMargin(string $instId, string $posSide, string $type, string $amt, ?string $ccy = null, ?bool $auto = null, ?string $loanTrans = null): array
    {
        $data = array_filter([
            'instId' => $instId,
            'posSide' => $posSide,
            'type' => $type,
            'amt' => $amt,
            'ccy' => $ccy,
            'auto' => $auto,
            'loanTrans' => $loanTrans,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/account/position/margin-balance', ['json' => $data]);
    }

    public function setAccountLevel(string $acctLv): array
    {
        return $this->client->request('POST', '/api/v5/account/set-account-level', [
            'json' => ['acctLv' => $acctLv],
        ]);
    }

    public function setAutoEarn(bool $autoEarn): array
    {
        return $this->client->request('POST', '/api/v5/account/set-auto-earn', [
            'json' => ['autoEarn' => $autoEarn],
        ]);
    }

    public function setAutoLoan(bool $autoLoan): array
    {
        return $this->client->request('POST', '/api/v5/account/set-auto-loan', [
            'json' => ['autoLoan' => $autoLoan],
        ]);
    }

    public function setAutoRepay(bool $autoRepay): array
    {
        return $this->client->request('POST', '/api/v5/account/set-auto-repay', [
            'json' => ['autoRepay' => $autoRepay],
        ]);
    }

    public function setCollateralAssets(array $ccy): array
    {
        return $this->client->request('POST', '/api/v5/account/set-collateral-assets', [
            'json' => ['ccy' => $ccy],
        ]);
    }

    public function setFeeType(string $feeType): array
    {
        return $this->client->request('POST', '/api/v5/account/set-fee-type', [
            'json' => ['feeType' => $feeType],
        ]);
    }

    public function setGreeks(string $greeksType): array
    {
        return $this->client->request('POST', '/api/v5/account/set-greeks', [
            'json' => ['greeksType' => $greeksType],
        ]);
    }

    public function setIsolatedMode(string $isoMode, string $type): array
    {
        return $this->client->request('POST', '/api/v5/account/set-isolated-mode', [
            'json' => [
                'isoMode' => $isoMode,
                'type' => $type,
            ],
        ]);
    }

    public function setLeverage(string $lever, ?string $mgnMode = null, ?string $instId = null, ?string $ccy = null, ?string $posSide = null): array
    {
        $data = array_filter([
            'lever' => $lever,
            'mgnMode' => $mgnMode,
            'instId' => $instId,
            'ccy' => $ccy,
            'posSide' => $posSide,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/account/set-leverage', ['json' => $data]);
    }

    public function setPositionMode(string $posMode): array
    {
        return $this->client->request('POST', '/api/v5/account/set-position-mode', [
            'json' => ['posMode' => $posMode],
        ]);
    }

    public function setRiskOffsetAmount(string $type, ?string $amt = null): array
    {
        $data = array_filter([
            'type' => $type,
            'amt' => $amt,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/account/set-riskOffset-amt', ['json' => $data]);
    }

    public function setSettleCurrency(string $settleCcy): array
    {
        return $this->client->request('POST', '/api/v5/account/set-settle-currency', [
            'json' => ['settleCcy' => $settleCcy],
        ]);
    }

    public function setTradingConfig(string $instType, ?bool $autoLoan = null, ?bool $autoRepay = null): array
    {
        $data = array_filter([
            'instType' => $instType,
            'autoLoan' => $autoLoan,
            'autoRepay' => $autoRepay,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/account/set-trading-config', ['json' => $data]);
    }

    public function spotManualBorrowRepay(string $ccy, string $side, string $amt): array
    {
        return $this->client->request('POST', '/api/v5/account/spot-manual-borrow-repay', [
            'json' => [
                'ccy' => $ccy,
                'side' => $side,
                'amt' => $amt,
            ],
        ]);
    }
}
