<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\DTO;

abstract class BaseDTO
{
    public function toArray(): array
    {
        return array_filter(
            get_object_vars($this),
            fn($v) => $v !== null
        );
    }
}
