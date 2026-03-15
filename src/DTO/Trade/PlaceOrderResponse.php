<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\DTO\Trade;

readonly class PlaceOrderResponse
{
    public function __construct(
        public string $ordId,
        public string $clOrdId,
        public string $tag,
        public string $sCode,
        public string $sMsg,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            ordId: $data['ordId'] ?? '',
            clOrdId: $data['clOrdId'] ?? '',
            tag: $data['tag'] ?? '',
            sCode: $data['sCode'] ?? '',
            sMsg: $data['sMsg'] ?? '',
        );
    }
}
