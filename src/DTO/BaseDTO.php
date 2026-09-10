<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\DTO;

abstract readonly class BaseDTO
{
    public function toArray(): array
    {
        return array_filter(
            array_map(self::toArrayValue(...), get_object_vars($this)),
            fn($v) => $v !== null
        );
    }

    private static function toArrayValue(mixed $value): mixed
    {
        if ($value instanceof self) {
            return $value->toArray();
        }

        if (is_array($value)) {
            return array_map(self::toArrayValue(...), $value);
        }

        return $value;
    }
}
