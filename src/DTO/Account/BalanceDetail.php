<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\DTO\Account;

readonly class BalanceDetail
{
    public function __construct(
        public string $ccy,
        public string $eq,
        public string $cashBal,
        public string $uTime,
        public string $isoEq,
        public string $availEq,
        public string $disEq,
        public string $availBal,
        public string $frozenBal,
        public string $ordFrozen,
        public string $liab,
        public string $upl,
        public string $uplLiab,
        public string $crossLiab,
        public string $isoLiab,
        public string $mgnRatio,
        public string $interest,
        public string $twap,
        public string $maxLoan,
        public string $eqUsd,
        public string $notionalLever,
        public string $stgyEq,
        public string $isoUpl,
        public string $spotInUseAmt,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            ccy: $data['ccy'] ?? '',
            eq: $data['eq'] ?? '0',
            cashBal: $data['cashBal'] ?? '0',
            uTime: $data['uTime'] ?? '',
            isoEq: $data['isoEq'] ?? '0',
            availEq: $data['availEq'] ?? '0',
            disEq: $data['disEq'] ?? '0',
            availBal: $data['availBal'] ?? '0',
            frozenBal: $data['frozenBal'] ?? '0',
            ordFrozen: $data['ordFrozen'] ?? '0',
            liab: $data['liab'] ?? '0',
            upl: $data['upl'] ?? '0',
            uplLiab: $data['uplLiab'] ?? '0',
            crossLiab: $data['crossLiab'] ?? '0',
            isoLiab: $data['isoLiab'] ?? '0',
            mgnRatio: $data['mgnRatio'] ?? '0',
            interest: $data['interest'] ?? '0',
            twap: $data['twap'] ?? '0',
            maxLoan: $data['maxLoan'] ?? '0',
            eqUsd: $data['eqUsd'] ?? '0',
            notionalLever: $data['notionalLever'] ?? '0',
            stgyEq: $data['stgyEq'] ?? '0',
            isoUpl: $data['isoUpl'] ?? '0',
            spotInUseAmt: $data['spotInUseAmt'] ?? '0',
        );
    }
}
