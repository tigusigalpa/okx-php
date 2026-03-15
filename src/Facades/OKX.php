<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Facades;

use Illuminate\Support\Facades\Facade;
use Tigusigalpa\OKX\Client;

class OKX extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Client::class;
    }
}
