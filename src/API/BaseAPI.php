<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

use Tigusigalpa\OKX\Client;

abstract class BaseAPI
{
    public function __construct(protected Client $client)
    {
    }
}
