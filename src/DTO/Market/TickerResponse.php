<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\DTO\Market;

readonly class TickerResponse
{
    public function __construct(
        public string $instType,
        public string $instId,
        public string $last,
        public string $lastSz,
        public string $askPx,
        public string $askSz,
        public string $bidPx,
        public string $bidSz,
        public string $open24h,
        public string $high24h,
        public string $low24h,
        public string $volCcy24h,
        public string $vol24h,
        public string $ts,
        public string $sodUtc0,
        public string $sodUtc8,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            instType: $data['instType'] ?? '',
            instId: $data['instId'] ?? '',
            last: $data['last'] ?? '0',
            lastSz: $data['lastSz'] ?? '0',
            askPx: $data['askPx'] ?? '0',
            askSz: $data['askSz'] ?? '0',
            bidPx: $data['bidPx'] ?? '0',
            bidSz: $data['bidSz'] ?? '0',
            open24h: $data['open24h'] ?? '0',
            high24h: $data['high24h'] ?? '0',
            low24h: $data['low24h'] ?? '0',
            volCcy24h: $data['volCcy24h'] ?? '0',
            vol24h: $data['vol24h'] ?? '0',
            ts: $data['ts'] ?? '',
            sodUtc0: $data['sodUtc0'] ?? '0',
            sodUtc8: $data['sodUtc8'] ?? '0',
        );
    }
}
