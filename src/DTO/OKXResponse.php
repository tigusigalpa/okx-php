<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\DTO;

readonly class OKXResponse
{
    public function __construct(
        public string $code,
        public string $msg,
        public array $data,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'] ?? '0',
            msg: $data['msg'] ?? '',
            data: $data['data'] ?? [],
        );
    }
}
