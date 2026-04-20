<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Finance extends BaseAPI
{
    public function getFixedLoanLendingApyHistory(?string $ccy = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/fixed-loan/lending-apy-history', ['query' => $query]);
    }

    public function getFixedLoanLendingOffers(?string $ccy = null, ?string $term = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'term' => $term,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/fixed-loan/lending-offers', ['query' => $query]);
    }

    public function getFlexibleLoanBorrowHistory(?string $ccy = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/flexible-loan/borrow-history', ['query' => $query]);
    }

    public function getFlexibleLoanCollateralAssets(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/finance/flexible-loan/collateral-assets', $options);
    }

    public function getFlexibleLoanInterestAccrued(?string $ccy = null, ?string $ordId = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'ordId' => $ordId,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/flexible-loan/interest-accrued', ['query' => $query]);
    }

    public function getFlexibleLoanInfo(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/finance/flexible-loan/loan-info', $options);
    }

    public function getFlexibleLoanPendingRepayAmount(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/finance/flexible-loan/pending-repay-amount', $options);
    }

    public function getSavingsBalance(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/finance/savings/balance', $options);
    }

    public function getSavingsLendingHistory(?string $ccy = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'ccy' => $ccy,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/savings/lending-history', ['query' => $query]);
    }

    public function getSavingsLendingRateHistory(string $ccy): array
    {
        return $this->client->request('GET', '/api/v5/finance/savings/lending-rate-history', [
            'query' => ['ccy' => $ccy],
        ]);
    }

    public function getSavingsLendingRateSummary(?string $ccy = null): array
    {
        $options = [];
        if ($ccy !== null) {
            $options['query'] = ['ccy' => $ccy];
        }
        return $this->client->request('GET', '/api/v5/finance/savings/lending-rate-summary', $options);
    }

    public function getStakingDefiEthApyHistory(): array
    {
        return $this->client->request('GET', '/api/v5/finance/staking-defi/eth/apy-history');
    }

    public function getStakingDefiEthBalance(): array
    {
        return $this->client->request('GET', '/api/v5/finance/staking-defi/eth/balance');
    }

    public function getStakingDefiEthPurchaseRedeemHistory(?string $type = null, ?string $status = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'type' => $type,
            'status' => $status,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/staking-defi/eth/purchase-redeem-history', ['query' => $query]);
    }

    public function getStakingDefiInvestData(string $productId, string $investData, ?string $ccy = null): array
    {
        $query = array_filter([
            'productId' => $productId,
            'investData' => $investData,
            'ccy' => $ccy,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/staking-defi/invest-data', ['query' => $query]);
    }

    public function getStakingDefiOffers(?string $productId = null, ?string $protocolType = null, ?string $ccy = null): array
    {
        $query = array_filter([
            'productId' => $productId,
            'protocolType' => $protocolType,
            'ccy' => $ccy,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/staking-defi/offers', ['query' => $query]);
    }

    public function getStakingDefiOrdersActivity(?string $productId = null, ?string $protocolType = null, ?string $ccy = null, ?string $state = null): array
    {
        $query = array_filter([
            'productId' => $productId,
            'protocolType' => $protocolType,
            'ccy' => $ccy,
            'state' => $state,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/staking-defi/orders-activity', ['query' => $query]);
    }

    public function getStakingDefiOrdersHistory(?string $productId = null, ?string $protocolType = null, ?string $ccy = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'productId' => $productId,
            'protocolType' => $protocolType,
            'ccy' => $ccy,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/staking-defi/orders-history', ['query' => $query]);
    }

    public function getStakingDefiSolApyHistory(): array
    {
        return $this->client->request('GET', '/api/v5/finance/staking-defi/sol/apy-history');
    }

    public function getStakingDefiSolBalance(): array
    {
        return $this->client->request('GET', '/api/v5/finance/staking-defi/sol/balance');
    }

    public function getStakingDefiSolPurchaseRedeemHistory(?string $type = null, ?string $status = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'type' => $type,
            'status' => $status,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/finance/staking-defi/sol/purchase-redeem-history', ['query' => $query]);
    }

    public function adjustFlexibleLoanCollateral(string $ordId, string $type, string $amt): array
    {
        return $this->client->request('POST', '/api/v5/finance/flexible-loan/adjust-collateral', [
            'json' => [
                'ordId' => $ordId,
                'type' => $type,
                'amt' => $amt,
            ],
        ]);
    }

    public function getFlexibleLoanMaxLoan(string $ccy): array
    {
        return $this->client->request('GET', '/api/v5/finance/flexible-loan/max-loan', [
            'query' => ['ccy' => $ccy],
        ]);
    }

    public function savingsPurchaseRedempt(string $ccy, string $amt, string $side, ?string $rate = null): array
    {
        $data = array_filter([
            'ccy' => $ccy,
            'amt' => $amt,
            'side' => $side,
            'rate' => $rate,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/finance/savings/purchase-redempt', ['json' => $data]);
    }

    public function setSavingsLendingRate(string $ccy, string $rate): array
    {
        return $this->client->request('POST', '/api/v5/finance/savings/set-lending-rate', [
            'json' => [
                'ccy' => $ccy,
                'rate' => $rate,
            ],
        ]);
    }

    public function cancelStakingDefi(string $ordId, string $protocolType): array
    {
        return $this->client->request('POST', '/api/v5/finance/staking-defi/cancel', [
            'json' => [
                'ordId' => $ordId,
                'protocolType' => $protocolType,
            ],
        ]);
    }

    public function cancelStakingDefiEthRedeem(string $ordId): array
    {
        return $this->client->request('POST', '/api/v5/finance/staking-defi/eth/cancel-redeem', [
            'json' => ['ordId' => $ordId],
        ]);
    }

    public function purchaseStakingDefiEth(string $amt): array
    {
        return $this->client->request('POST', '/api/v5/finance/staking-defi/eth/purchase', [
            'json' => ['amt' => $amt],
        ]);
    }

    public function redeemStakingDefiEth(string $amt): array
    {
        return $this->client->request('POST', '/api/v5/finance/staking-defi/eth/redeem', [
            'json' => ['amt' => $amt],
        ]);
    }

    public function purchaseStakingDefi(string $productId, string $investData, string $amt, ?string $term = null, ?string $tag = null): array
    {
        $data = array_filter([
            'productId' => $productId,
            'investData' => $investData,
            'amt' => $amt,
            'term' => $term,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/finance/staking-defi/purchase', ['json' => $data]);
    }

    public function redeemStakingDefi(string $ordId, string $protocolType, ?string $allowEarlyRedeem = null): array
    {
        $data = array_filter([
            'ordId' => $ordId,
            'protocolType' => $protocolType,
            'allowEarlyRedeem' => $allowEarlyRedeem,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/finance/staking-defi/redeem', ['json' => $data]);
    }

    public function purchaseStakingDefiSol(string $amt): array
    {
        return $this->client->request('POST', '/api/v5/finance/staking-defi/sol/purchase', [
            'json' => ['amt' => $amt],
        ]);
    }

    public function redeemStakingDefiSol(string $amt): array
    {
        return $this->client->request('POST', '/api/v5/finance/staking-defi/sol/redeem', [
            'json' => ['amt' => $amt],
        ]);
    }
}
