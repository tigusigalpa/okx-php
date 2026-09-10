<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\DTO\Trade;

use Tigusigalpa\OKX\DTO\BaseDTO;

/**
 * Request-only shape for a TP/SL order attached to a main order.
 *
 * Set tpOrdPx or slOrdPx to "-1" to execute that leg at market price.
 */
readonly class AttachAlgoOrderRequest extends BaseDTO
{
    public function __construct(
        public ?string $attachAlgoClOrdId = null,
        public ?string $tpOrdPx = null,
        public ?string $tpTriggerPx = null,
        public ?string $tpTriggerPxType = null,
        public ?string $slOrdPx = null,
        public ?string $slTriggerPx = null,
        public ?string $slTriggerPxType = null,
        public ?string $sz = null,
        public ?string $amendPxOnTriggerType = null,
    ) {
    }
}
