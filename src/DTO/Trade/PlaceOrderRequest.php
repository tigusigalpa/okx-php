<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\DTO\Trade;

use Tigusigalpa\OKX\DTO\BaseDTO;

readonly class PlaceOrderRequest extends BaseDTO
{
    public function __construct(
        public string $instId,
        public string $tdMode,
        public string $side,
        public string $ordType,
        public string $sz,
        public ?string $px = null,
        public ?string $ccy = null,
        public ?string $clOrdId = null,
        public ?string $tag = null,
        public ?string $posSide = null,
        public ?bool $reduceOnly = null,
        public ?string $tgtCcy = null,
        public ?bool $banAmend = null,
        public ?string $tpTriggerPx = null,
        public ?string $tpOrdPx = null,
        public ?string $slTriggerPx = null,
        public ?string $slOrdPx = null,
        public ?string $tpTriggerPxType = null,
        public ?string $slTriggerPxType = null,
        public ?string $quickMgnType = null,
        public ?string $stpId = null,
        public ?string $stpMode = null,
    ) {
    }
}
