<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Support extends BaseAPI
{
    public function getAnnouncementTypes(): array
    {
        return $this->client->request('GET', '/api/v5/support/announcement-types');
    }

    public function getAnnouncements(?string $annType = null, ?int $page = null, ?int $limit = null): array
    {
        $query = array_filter([
            'annType' => $annType,
            'page' => $page,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/support/announcements', ['query' => $query]);
    }
}
