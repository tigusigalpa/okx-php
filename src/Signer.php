<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX;

class Signer
{
    public function __construct(
        private readonly string $secretKey,
    ) {
    }

    public function generateTimestamp(): string
    {
        $microtime = microtime(true);
        $milliseconds = sprintf('%03d', ($microtime - floor($microtime)) * 1000);
        
        return gmdate('Y-m-d\TH:i:s.', (int) $microtime) . $milliseconds . 'Z';
    }

    public function sign(string $timestamp, string $method, string $requestPath, string $body = ''): string
    {
        $prehash = $timestamp . strtoupper($method) . $requestPath . $body;
        $signature = hash_hmac('sha256', $prehash, $this->secretKey, true);
        
        return base64_encode($signature);
    }
}
