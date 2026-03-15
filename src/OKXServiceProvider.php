<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX;

use Illuminate\Support\ServiceProvider;

class OKXServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/okx.php', 'okx');

        $this->app->singleton(Client::class, function ($app) {
            return new Client(
                apiKey: config('okx.api_key'),
                secretKey: config('okx.secret_key'),
                passphrase: config('okx.passphrase'),
                isDemo: config('okx.demo', false),
                baseUrl: config('okx.base_url', 'https://www.okx.com'),
            );
        });

        $this->app->singleton(WebsocketClient::class, function ($app) {
            return new WebsocketClient(
                apiKey: config('okx.api_key'),
                secretKey: config('okx.secret_key'),
                passphrase: config('okx.passphrase'),
                isDemo: config('okx.demo', false),
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/okx.php' => config_path('okx.php'),
            ], 'okx-config');
        }
    }
}
