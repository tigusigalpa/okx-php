<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Tests\Unit;

use Tigusigalpa\OKX\Client;
use Tigusigalpa\OKX\Region;
use Tigusigalpa\OKX\Tests\TestCase;

class RegionTest extends TestCase
{
    public function test_regions_resolve_to_the_official_rest_domains(): void
    {
        $this->assertSame('https://openapi.okx.com', Region::Global->restBaseUrl());
        $this->assertSame('https://us.okx.com', Region::resolve('AU')->restBaseUrl());
        $this->assertSame('https://eea.okx.com', Region::resolve('eu')->restBaseUrl());
        $this->assertSame('https://tr.okx.com', Region::resolve('turkey')->restBaseUrl());
    }

    public function test_regions_resolve_to_the_official_websocket_domains(): void
    {
        $this->assertSame(
            'wss://wsus.okx.com:8443/ws/v5/private',
            Region::Us->websocketUrl('private')
        );
        $this->assertSame(
            'wss://wseeapap.okx.com:8443/ws/v5/business',
            Region::Eea->websocketUrl('business', true)
        );
        $this->assertSame(
            'wss://ws.okx.com:8443/ws/v5/public',
            Region::Turkey->websocketUrl('public')
        );
    }

    public function test_client_uses_the_selected_region_unless_a_custom_url_is_provided(): void
    {
        $regionalClient = new Client('key', 'secret', 'passphrase', region: Region::Eea);
        $customClient = new Client('key', 'secret', 'passphrase', baseUrl: 'https://proxy.example/');

        $this->assertSame('https://eea.okx.com', $regionalClient->baseUrl());
        $this->assertSame('https://proxy.example', $customClient->baseUrl());
    }
}
