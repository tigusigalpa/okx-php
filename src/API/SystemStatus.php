<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class SystemStatus extends BaseAPI
{
    public function getStatus(?string $state = null): array
    {
        $options = [];
        if ($state !== null) {
            $options['query'] = ['state' => $state];
        }
        return $this->client->request('GET', '/api/v5/system/status', $options);
    }
}
