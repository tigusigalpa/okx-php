<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\DTO\Account;

readonly class BalanceResponse
{
    public function __construct(
        public string $uTime,
        public string $totalEq,
        public string $isoEq,
        public string $adjEq,
        public string $ordFroz,
        public string $imr,
        public string $mmr,
        public string $mgnRatio,
        public string $notionalUsd,
        public array $details,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            uTime: $data['uTime'] ?? '',
            totalEq: $data['totalEq'] ?? '0',
            isoEq: $data['isoEq'] ?? '0',
            adjEq: $data['adjEq'] ?? '0',
            ordFroz: $data['ordFroz'] ?? '0',
            imr: $data['imr'] ?? '0',
            mmr: $data['mmr'] ?? '0',
            mgnRatio: $data['mgnRatio'] ?? '0',
            notionalUsd: $data['notionalUsd'] ?? '0',
            details: array_map(
                fn($detail) => BalanceDetail::fromArray($detail),
                $data['details'] ?? []
            ),
        );
    }
}
