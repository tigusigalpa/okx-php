<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class Users extends BaseAPI
{
    public function getEntrustSubaccountList(?string $subAcct = null): array
    {
        $options = [];
        if ($subAcct !== null) {
            $options['query'] = ['subAcct' => $subAcct];
        }
        return $this->client->request('GET', '/api/v5/users/entrust-subaccount-list', $options);
    }

    public function getSubaccountApikey(string $subAcct, ?string $apiKey = null): array
    {
        $query = array_filter([
            'subAcct' => $subAcct,
            'apiKey' => $apiKey,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/users/subaccount/apikey', ['query' => $query]);
    }

    public function getSubaccountList(?bool $enable = null, ?string $subAcct = null, ?string $after = null, ?string $before = null, ?int $limit = null): array
    {
        $query = array_filter([
            'enable' => $enable,
            'subAcct' => $subAcct,
            'after' => $after,
            'before' => $before,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/users/subaccount/list', ['query' => $query]);
    }

    public function createSubaccountApikey(string $subAcct, string $label, string $passphrase, ?array $ip = null, ?string $perm = null): array
    {
        $data = array_filter([
            'subAcct' => $subAcct,
            'label' => $label,
            'passphrase' => $passphrase,
            'ip' => $ip,
            'perm' => $perm,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/users/subaccount/apikey', ['json' => $data]);
    }

    public function createSubaccount(string $subAcct, string $pwd, ?string $label = null): array
    {
        $data = array_filter([
            'subAcct' => $subAcct,
            'pwd' => $pwd,
            'label' => $label,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/users/subaccount/create-subaccount', ['json' => $data]);
    }

    public function deleteSubaccountApikey(string $subAcct, string $apiKey): array
    {
        return $this->client->request('POST', '/api/v5/users/subaccount/delete-apikey', [
            'json' => [
                'subAcct' => $subAcct,
                'apiKey' => $apiKey,
            ],
        ]);
    }

    public function modifySubaccountApikey(string $subAcct, string $apiKey, ?string $label = null, ?string $perm = null, ?array $ip = null): array
    {
        $data = array_filter([
            'subAcct' => $subAcct,
            'apiKey' => $apiKey,
            'label' => $label,
            'perm' => $perm,
            'ip' => $ip,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/users/subaccount/modify-apikey', ['json' => $data]);
    }

    public function setSubaccountTransferOut(string $subAcct, ?bool $canTransOut = null): array
    {
        $data = array_filter([
            'subAcct' => $subAcct,
            'canTransOut' => $canTransOut,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/users/subaccount/set-transfer-out', ['json' => $data]);
    }
}
