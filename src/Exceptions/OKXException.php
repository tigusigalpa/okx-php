<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Exceptions;

class OKXException extends \RuntimeException
{
    public function __construct(
        public readonly string $okxCode,
        string $okxMessage,
        public readonly string $rawResponse = '',
        ?\Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf('OKX API error: code=%s, message=%s', $okxCode, $okxMessage),
            (int) $okxCode,
            $previous
        );
    }
}
