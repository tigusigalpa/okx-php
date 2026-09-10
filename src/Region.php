<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX;

use InvalidArgumentException;

/**
 * OKX account region determines the REST and WebSocket API domains.
 *
 * US also covers accounts registered through app.okx.com in Australia.
 */
enum Region: string
{
    case Global = 'global';
    case Us = 'us';
    case Eea = 'eea';
    case Turkey = 'tr';

    public static function resolve(self|string $region): self
    {
        if ($region instanceof self) {
            return $region;
        }

        return match (strtolower(trim($region))) {
            'global', 'default' => self::Global,
            'us', 'au' => self::Us,
            'eea', 'eu' => self::Eea,
            'tr', 'turkey' => self::Turkey,
            default => throw new InvalidArgumentException(sprintf(
                'Unsupported OKX region "%s". Use "global", "us" (also AU), "eea", or "tr".',
                $region
            )),
        };
    }

    public function restBaseUrl(): string
    {
        return match ($this) {
            self::Global => 'https://openapi.okx.com',
            self::Us => 'https://us.okx.com',
            self::Eea => 'https://eea.okx.com',
            self::Turkey => 'https://tr.okx.com',
        };
    }

    public function websocketUrl(string $channel, bool $isDemo = false): string
    {
        if (!in_array($channel, ['public', 'private', 'business'], true)) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported OKX WebSocket channel "%s".',
                $channel
            ));
        }

        $host = match ($this) {
            self::Global => $isDemo ? 'wspap.okx.com' : 'ws.okx.com',
            self::Us => $isDemo ? 'wsuspap.okx.com' : 'wsus.okx.com',
            self::Eea => $isDemo ? 'wseeapap.okx.com' : 'wseea.okx.com',
            // OKX Turkey documents the shared global WebSocket endpoints.
            self::Turkey => $isDemo ? 'wspap.okx.com' : 'ws.okx.com',
        };

        return sprintf('wss://%s:8443/ws/v5/%s', $host, $channel);
    }
}
