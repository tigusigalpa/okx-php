# OKX PHP/Laravel Client/SDK/Library

![OKX PHP client](https://i.postimg.cc/pTbK8Xsb/okx-php-laravel-hero.jpg)

[![Tests](https://github.com/tigusigalpa/okx-php/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/tigusigalpa/okx-php/actions/workflows/tests.yml)
[![Coverage](https://github.com/tigusigalpa/okx-php/actions/workflows/coverage.yml/badge.svg?branch=main)](https://github.com/tigusigalpa/okx-php/actions/workflows/coverage.yml)
[![Quality](https://github.com/tigusigalpa/okx-php/actions/workflows/quality.yml/badge.svg?branch=main)](https://github.com/tigusigalpa/okx-php/actions/workflows/quality.yml)
[![CodeQL](https://github.com/tigusigalpa/okx-php/actions/workflows/codeql.yml/badge.svg?branch=main)](https://github.com/tigusigalpa/okx-php/actions/workflows/codeql.yml)
[![codecov](https://codecov.io/gh/tigusigalpa/okx-php/graph/badge.svg)](https://codecov.io/gh/tigusigalpa/okx-php)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

PHP client for the [OKX v5 API](https://www.okx.com/docs-v5/en/). Covers the entire REST API (335 endpoints, 16
categories) and WebSocket API (53 channels). Works standalone or plugs into Laravel 11/12/13 via a Service Provider.

Go
version: [tigusigalpa/okx-go](https://github.com/tigusigalpa/okx-go) | [Wiki](https://github.com/tigusigalpa/okx-php/wiki)

## What's inside

- 335 REST endpoints grouped into service classes (`account()`, `trade()`, `market()`, etc.)
- WebSocket client with auto-reconnect and ping/pong heartbeat
- HMAC-SHA256 request signing
- Demo trading mode (just flip a flag)
- Typed DTOs for requests and responses; all prices/amounts stay as `string` to avoid float rounding
- Laravel Service Provider, Facade, publishable config
- PSR-3 logging, PSR-12 code style
- PHP 8.2+ (`readonly` classes, named arguments)

## Requirements

- PHP 8.2+
- Composer
- Laravel 11, 12 or 13 (optional)

## Installation

```bash
composer require tigusigalpa/okx-php
```

## Laravel setup

Publish the config:

```bash
php artisan vendor:publish --tag=okx-config
```

Add credentials to `.env`:

```env
OKX_API_KEY=your-api-key
OKX_SECRET_KEY=your-secret-key
OKX_PASSPHRASE=your-passphrase
OKX_DEMO=false
```

Then use the facade:

```php
use Tigusigalpa\OKX\Facades\OKX;

$balance = OKX::account()->getBalance();

$order = OKX::trade()->placeOrder(
    instId: 'BTC-USDT',
    tdMode: 'cash',
    side: 'buy',
    ordType: 'limit',
    sz: '0.01',
    px: '50000'
);

$ticker = OKX::market()->getTicker('BTC-USDT');
```

## Standalone usage

```php
use Tigusigalpa\OKX\Client;

$client = new Client(
    apiKey: 'your-api-key',
    secretKey: 'your-secret-key',
    passphrase: 'your-passphrase',
    // Region::Us for US/AU accounts; Region::Eea for my.okx.com accounts.
    region: \Tigusigalpa\OKX\Region::Global,
);

$balance = $client->account()->getBalance();

$order = $client->trade()->placeOrder(
    instId: 'BTC-USDT-SWAP',
    tdMode: 'cash',
    side: 'buy',
    ordType: 'market',
    sz: '100',
    tgtCcy: 'quote_ccy'
);

$trades = $client->market()->getTrades('BTC-USDT', limit: 100);
```

## Configuration

| Option       | Env variable     | Default                  |                              |
|--------------|------------------|--------------------------|------------------------------|
| `api_key`    | `OKX_API_KEY`    | `''`                     | API key                      |
| `secret_key` | `OKX_SECRET_KEY` | `''`                     | Secret key                   |
| `passphrase` | `OKX_PASSPHRASE` | `''`                     | Passphrase                   |
| `demo`       | `OKX_DEMO`       | `false`                  | Use demo trading environment |
| `region`     | `OKX_REGION`     | `global`                 | `global`, `us` (also AU), `eea`, or `tr` |
| `base_url`   | `OKX_BASE_URL`   | Region-specific endpoint | Optional REST endpoint override |

## Regional API domains

OKX requires the API domain to match the region where the account was registered. Set `OKX_REGION` once; the REST and
WebSocket clients then select the matching live and demo endpoints.

| Account registration | `OKX_REGION` | REST endpoint | Live WebSocket host |
|----------------------|--------------|---------------|---------------------|
| Other regions | `global` | `https://openapi.okx.com` | `wss://ws.okx.com:8443` |
| US or AU (`app.okx.com`) | `us` | `https://us.okx.com` | `wss://wsus.okx.com:8443` |
| EEA (`my.okx.com`) | `eea` | `https://eea.okx.com` | `wss://wseea.okx.com:8443` |
| Turkey | `tr` | `https://tr.okx.com` | `wss://ws.okx.com:8443` |

For a standalone client, pass `region: Region::Us` or `region: Region::Eea`. `OKX_BASE_URL` remains available for a
proxy or another explicit REST override. It does not change WebSocket hosts.

## REST API coverage

335 endpoints across 16 categories:

| Category       | #  | Docs                                                                                    |
|----------------|----|-----------------------------------------------------------------------------------------|
| Account        | 53 | [Trading Account](https://www.okx.com/docs-v5/en/#trading-account-rest-api)             |
| Trade          | 32 | [Order Book Trading](https://www.okx.com/docs-v5/en/#order-book-trading-trade-rest-api) |
| Market         | 24 | [Market Data](https://www.okx.com/docs-v5/en/#order-book-trading-market-data-rest-api)  |
| Public Data    | 24 | [Public Data](https://www.okx.com/docs-v5/en/#public-data-rest-api)                     |
| Asset          | 26 | [Funding Account](https://www.okx.com/docs-v5/en/#funding-account-rest-api)             |
| Finance        | 33 | [Financial Products](https://www.okx.com/docs-v5/en/#financial-product-rest-api)        |
| Copy Trading   | 26 | [Copy Trading](https://www.okx.com/docs-v5/en/#copy-trading-rest-api)                   |
| Trading Bot    | 44 | [Trading Bot](https://www.okx.com/docs-v5/en/#trading-bot-grid-trading-rest-api)        |
| RFQ            | 20 | [Block Trading](https://www.okx.com/docs-v5/en/#block-trading-rest-api)                 |
| Spread Trading | 13 | [Spread Trading](https://www.okx.com/docs-v5/en/#spread-trading-rest-api)               |
| Rubik          | 15 | [Trading Statistics](https://www.okx.com/docs-v5/en/#trading-statistics-rest-api)       |
| Fiat           | 13 | [Fiat](https://www.okx.com/docs-v5/en/#fiat-rest-api)                                   |
| Users          | 8  | [Sub-account](https://www.okx.com/docs-v5/en/#sub-account-rest-api)                     |
| Support        | 2  | [Announcements](https://www.okx.com/docs-v5/en/#announcement-rest-api)                  |
| System Status  | 1  | [Status](https://www.okx.com/docs-v5/en/#status-rest-api)                               |
| Affiliate      | 1  | [Affiliate](https://www.okx.com/docs-v5/en/#affiliate-rest-api)                         |

## WebSocket

Public channel:

```php
use Tigusigalpa\OKX\WebsocketClient;

$ws = new WebsocketClient(
    apiKey: 'your-api-key',
    secretKey: 'your-secret-key',
    passphrase: 'your-passphrase',
    region: \Tigusigalpa\OKX\Region::Eea,
);

$ws->connectPublic();

$ws->subscribe('tickers', ['instId' => 'BTC-USDT'], function ($data) {
    echo "BTC-USDT: " . $data['data'][0]['last'] . PHP_EOL;
});

$ws->run();
```

Private channel (authenticated automatically on connect):

```php
$ws->connectPrivate();

$ws->subscribe('account', ['ccy' => 'BTC'], function ($data) {
    foreach ($data['data'] as $account) {
        echo "Balance: {$account['bal']} {$account['ccy']}" . PHP_EOL;
    }
});

$ws->run();
```

For a public channel served from the business endpoint, call `connectBusiness(requiresAuth: false)`.

Public channels include `tickers`, `books`, `books5`, `trades`, `candle*`, `index-tickers`, `mark-price`,
`funding-rate`, `open-interest`, `liquidation-orders`, and others.

Private channels include `account`, `positions`, `orders`, `orders-algo`, `fills`, `balance_and_position`,
`liquidation-warning`, `deposit-info`, `withdrawal-info`, and others.

## Demo trading

Set `OKX_DEMO=true` in `.env`, or pass `isDemo: true` to the constructor. Same API, sandbox environment, no real money
involved.

```php
$client = new Client(
    apiKey: 'your-api-key',
    secretKey: 'your-secret-key',
    passphrase: 'your-passphrase',
    isDemo: true
);
```

## Examples

Order with attached TP/SL:

```php
$order = $client->trade()->placeOrder(
    instId: 'BTC-USDT',
    tdMode: 'isolated',
    side: 'buy',
    ordType: 'market',
    sz: '0.1',
    attachAlgoOrds: [[
        'tpTriggerPx' => '55000',
        'tpOrdPx' => '-1', // -1 executes this TP at market price after triggering
        'tpTriggerPxType' => 'last', // last, index, or mark
        'slTriggerPx' => '48000',
        'slOrdPx' => '-1',
        'slTriggerPxType' => 'last',
    ]],
);
```

OKX may reject the legacy inline TP/SL fields with `sCode=54070`; prefer `attachAlgoOrds`. A typed request form is
also available when it fits the application better:

```php
use Tigusigalpa\OKX\DTO\Trade\AttachAlgoOrderRequest;
use Tigusigalpa\OKX\DTO\Trade\PlaceOrderRequest;

$order = $client->trade()->placeOrderRequest(new PlaceOrderRequest(
    instId: 'BTC-USDT-SWAP',
    tdMode: 'isolated',
    side: 'sell',
    ordType: 'market',
    sz: '1',
    attachAlgoOrds: [new AttachAlgoOrderRequest(
        tpTriggerPx: '64000',
        tpOrdPx: '-1',
        tpTriggerPxType: 'last',
        slTriggerPx: '66000',
        slOrdPx: '-1',
        slTriggerPxType: 'last',
    )],
));
```

Batch orders:

```php
$orders = $client->trade()->batchOrders([
    [
        'instId' => 'BTC-USDT',
        'tdMode' => 'cash',
        'side' => 'buy',
        'ordType' => 'limit',
        'sz' => '0.01',
        'px' => '50000'
    ],
    [
        'instId' => 'ETH-USDT',
        'tdMode' => 'cash',
        'side' => 'buy',
        'ordType' => 'limit',
        'sz' => '0.1',
        'px' => '3000'
    ]
]);
```

Set leverage:

```php
$client->account()->setLeverage(
    lever: '10',
    mgnMode: 'cross',
    instId: 'BTC-USDT-SWAP'
);
```

Historical candles:

```php
$candles = $client->market()->getHistoryCandles(
    instId: 'BTC-USDT',
    bar: '1H',
    limit: 100
);
```

Withdrawal:

```php
$withdrawal = $client->asset()->withdrawal(
    ccy: 'USDT',
    amt: '100',
    dest: '4',
    toAddr: 'your-wallet-address',
    fee: '1',
    chain: 'USDT-TRC20'
);
```

## Error handling

Each error type has its own exception class. They all extend `OKXException`, which carries the raw OKX error code in
`$e->okxCode`. The decoded response envelope is also available in `$e->response`, so order-specific `data[0].sCode`
and `sMsg` are not lost when the top-level API code is non-zero.

```php
use Tigusigalpa\OKX\Exceptions\AuthenticationException;
use Tigusigalpa\OKX\Exceptions\RateLimitException;
use Tigusigalpa\OKX\Exceptions\InvalidParameterException;
use Tigusigalpa\OKX\Exceptions\InsufficientFundsException;
use Tigusigalpa\OKX\Exceptions\OKXException;

try {
    $balance = $client->account()->getBalance();
} catch (AuthenticationException $e) {
    // bad credentials or signature
} catch (RateLimitException $e) {
    // slow down
} catch (InvalidParameterException $e) {
    // check your request params
} catch (InsufficientFundsException $e) {
    // not enough balance
} catch (OKXException $e) {
    // everything else from OKX
    echo "OKX error [{$e->okxCode}]: " . $e->getMessage();
}
```

## Logging

Pass any PSR-3 logger to the constructor:

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('okx');
$logger->pushHandler(new StreamHandler('okx.log', Logger::DEBUG));

$client = new Client(
    apiKey: 'your-api-key',
    secretKey: 'your-secret-key',
    passphrase: 'your-passphrase',
    logger: $logger
);
```

Credentials are never written to logs.

## Testing

Unit tests:

```bash
vendor/bin/phpunit
```

Integration tests (needs demo credentials):

```bash
export OKX_API_KEY=your-demo-api-key
export OKX_SECRET_KEY=your-demo-secret-key
export OKX_PASSPHRASE=your-demo-passphrase
vendor/bin/phpunit -c phpunit.integration.xml
```

## Contributing

1. Fork it
2. Create a branch (`git checkout -b fix/something`)
3. Make your changes, add tests
4. Push and open a PR

Code must follow PSR-12. Tests must pass.

## Security

Found a vulnerability? Email sovletig@gmail.com directly. Don't open a public issue.

## License

MIT. See [LICENSE](LICENSE).

## Links

- [OKX API v5 docs](https://www.okx.com/docs-v5/en/)
- [Repository](https://github.com/tigusigalpa/okx-php)
- [Issues](https://github.com/tigusigalpa/okx-php/issues)
