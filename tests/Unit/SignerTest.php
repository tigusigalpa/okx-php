<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Tests\Unit;

use Tigusigalpa\OKX\Signer;
use Tigusigalpa\OKX\Tests\TestCase;

class SignerTest extends TestCase
{
    private Signer $signer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->signer = new Signer('test-secret-key');
    }

    public function test_generate_timestamp_format(): void
    {
        $timestamp = $this->signer->generateTimestamp();

        $this->assertMatchesRegularExpression(
            '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z$/',
            $timestamp,
            'Timestamp should be in ISO 8601 format with milliseconds'
        );
    }

    public function test_sign_generates_valid_signature(): void
    {
        $timestamp = '2020-12-08T09:08:57.715Z';
        $method = 'GET';
        $requestPath = '/api/v5/account/balance';
        $body = '';

        $signature = $this->signer->sign($timestamp, $method, $requestPath, $body);

        $this->assertNotEmpty($signature);
        $this->assertMatchesRegularExpression(
            '/^[A-Za-z0-9+\/=]+$/',
            $signature,
            'Signature should be base64 encoded'
        );
    }

    public function test_sign_with_post_body(): void
    {
        $timestamp = '2020-12-08T09:08:57.715Z';
        $method = 'POST';
        $requestPath = '/api/v5/trade/order';
        $body = '{"instId":"BTC-USDT","tdMode":"cash","side":"buy","ordType":"limit","sz":"0.01","px":"50000"}';

        $signature = $this->signer->sign($timestamp, $method, $requestPath, $body);

        $this->assertNotEmpty($signature);
    }

    public function test_sign_is_deterministic(): void
    {
        $timestamp = '2020-12-08T09:08:57.715Z';
        $method = 'GET';
        $requestPath = '/api/v5/account/balance';
        $body = '';

        $signature1 = $this->signer->sign($timestamp, $method, $requestPath, $body);
        $signature2 = $this->signer->sign($timestamp, $method, $requestPath, $body);

        $this->assertEquals($signature1, $signature2);
    }
}
