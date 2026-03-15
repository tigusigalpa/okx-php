<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Affiliate extends BaseAPI
{
    public function getInviteeDetail(string $uid): array
    {
        return $this->client->request('GET', '/api/v5/affiliate/invitee/detail', [
            'query' => ['uid' => $uid],
        ]);
    }
}
