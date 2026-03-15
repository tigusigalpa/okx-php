# Быстрый старт

Начните работу с OKX PHP SDK за несколько минут.

## Laravel Quick Start

### 1. Базовая настройка

После [установки](Installation), добавьте в `.env`:

```env
OKX_API_KEY=your-api-key
OKX_SECRET_KEY=your-secret-key
OKX_PASSPHRASE=your-passphrase
```

### 2. Использование Facade

```php
use Tigusigalpa\OKX\Facades\OKX;

// Получить баланс аккаунта
$balance = OKX::account()->getBalance();

// Получить информацию о конкретной валюте
$btcBalance = OKX::account()->getBalance('BTC');
```

### 3. Размещение ордера

```php
// Market ордер (покупка за USDT)
$order = OKX::trade()->placeOrder(
    instId: 'BTC-USDT',
    tdMode: 'cash',
    side: 'buy',
    ordType: 'market',
    sz: '100',        // Купить на 100 USDT
    tgtCcy: 'quote_ccy'
);

// Limit ордер
$order = OKX::trade()->placeOrder(
    instId: 'BTC-USDT',
    tdMode: 'cash',
    side: 'buy',
    ordType: 'limit',
    sz: '0.01',       // Количество BTC
    px: '50000'       // Цена
);
```

### 4. Получение рыночных данных

```php
// Текущая цена
$ticker = OKX::market()->getTicker('BTC-USDT');
echo "BTC цена: " . $ticker[0]['last'];

// Последние сделки
$trades = OKX::market()->getTrades('BTC-USDT', limit: 10);

// Свечи (candlesticks)
$candles = OKX::market()->getCandles(
    instId: 'BTC-USDT',
    bar: '1H',
    limit: 100
);
```

### 5. Управление ордерами

```php
// Получить активные ордера
$orders = OKX::trade()->getOrdersPending(
    instType: 'SPOT'
);

// Отменить ордер
$result = OKX::trade()->cancelOrder(
    instId: 'BTC-USDT',
    ordId: '123456789'
);

// Получить историю ордеров
$history = OKX::trade()->getOrdersHistory(
    instType: 'SPOT',
    limit: 50
);
```

## Standalone Quick Start

### 1. Создание клиента

```php
use Tigusigalpa\OKX\Client;

$client = new Client(
    apiKey: 'your-api-key',
    secretKey: 'your-secret-key',
    passphrase: 'your-passphrase',
    isDemo: false
);
```

### 2. Получение данных

```php
// Баланс
$balance = $client->account()->getBalance();

// Рыночные данные
$ticker = $client->market()->getTicker('BTC-USDT');

// Публичные данные (не требуют аутентификации)
$instruments = $client->publicData()->getInstruments(
    instType: 'SPOT'
);
```

### 3. Торговля

```php
// Разместить ордер
$order = $client->trade()->placeOrder(
    instId: 'ETH-USDT',
    tdMode: 'cash',
    side: 'sell',
    ordType: 'limit',
    sz: '0.1',
    px: '3000'
);

// Проверить статус ордера
$orderInfo = $client->trade()->getOrder(
    instId: 'ETH-USDT',
    ordId: $order[0]['ordId']
);
```

## WebSocket Quick Start

### 1. Подключение к публичным каналам

```php
use Tigusigalpa\OKX\WebsocketClient;

$ws = new WebsocketClient(
    apiKey: 'your-api-key',
    secretKey: 'your-secret-key',
    passphrase: 'your-passphrase'
);

// Подключиться к публичному каналу
$ws->connectPublic();

// Подписаться на тикеры
$ws->subscribe('tickers', ['instId' => 'BTC-USDT'], function ($data) {
    $ticker = $data['data'][0];
    echo "BTC-USDT: {$ticker['last']}\n";
});

// Запустить прослушивание
$ws->run();
```

### 2. Подключение к приватным каналам

```php
// Подключиться к приватному каналу
$ws->connectPrivate();

// Подписаться на обновления аккаунта
$ws->subscribe('account', ['ccy' => 'BTC'], function ($data) {
    foreach ($data['data'] as $account) {
        echo "Баланс {$account['ccy']}: {$account['bal']}\n";
    }
});

// Подписаться на ордера
$ws->subscribe('orders', ['instType' => 'SPOT'], function ($data) {
    foreach ($data['data'] as $order) {
        echo "Ордер {$order['ordId']}: {$order['state']}\n";
    }
});

$ws->run();
```

## Распространенные сценарии

### Проверка баланса перед торговлей

```php
$balance = OKX::account()->getBalance('USDT');
$availableBalance = $balance[0]['details'][0]['availBal'];

if ((float)$availableBalance >= 100) {
    $order = OKX::trade()->placeOrder(
        instId: 'BTC-USDT',
        tdMode: 'cash',
        side: 'buy',
        ordType: 'market',
        sz: '100',
        tgtCcy: 'quote_ccy'
    );
} else {
    echo "Недостаточно средств";
}
```

### Получение текущей цены

```php
function getCurrentPrice(string $symbol): string
{
    $ticker = OKX::market()->getTicker($symbol);
    return $ticker[0]['last'];
}

$btcPrice = getCurrentPrice('BTC-USDT');
echo "BTC: $" . $btcPrice;
```

### Размещение ордера с Stop Loss и Take Profit

```php
$order = OKX::trade()->placeOrder(
    instId: 'BTC-USDT',
    tdMode: 'cross',
    side: 'buy',
    ordType: 'limit',
    sz: '0.1',
    px: '50000',
    tpTriggerPx: '55000',  // Take Profit
    tpOrdPx: '-1',         // Market order
    slTriggerPx: '48000',  // Stop Loss
    slOrdPx: '-1'          // Market order
);
```

### Пакетное размещение ордеров

```php
$orders = OKX::trade()->batchOrders([
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

### Получение исторических свечей

```php
$candles = OKX::market()->getHistoryCandles(
    instId: 'BTC-USDT',
    bar: '1D',      // 1 день
    limit: 365      // За год
);

foreach ($candles as $candle) {
    [$timestamp, $open, $high, $low, $close, $volume] = $candle;
    echo date('Y-m-d', $timestamp / 1000) . ": $close\n";
}
```

## Обработка ошибок

```php
use Tigusigalpa\OKX\Exceptions\OKXException;
use Tigusigalpa\OKX\Exceptions\RateLimitException;

try {
    $order = OKX::trade()->placeOrder(
        instId: 'BTC-USDT',
        tdMode: 'cash',
        side: 'buy',
        ordType: 'market',
        sz: '100'
    );
} catch (RateLimitException $e) {
    // Превышен лимит запросов
    sleep(1);
    // Повторить запрос
} catch (OKXException $e) {
    // Другие ошибки OKX
    echo "Ошибка [{$e->okxCode}]: {$e->getMessage()}";
}
```

## Demo режим

Для тестирования без риска:

```php
// В .env
OKX_DEMO=true

// Или в коде
$client = new Client(
    apiKey: 'demo-api-key',
    secretKey: 'demo-secret-key',
    passphrase: 'demo-passphrase',
    isDemo: true
);
```

## Следующие шаги

- [REST API документация](REST-API) - Полный список методов
- [WebSocket документация](WebSocket-API) - Работа с real-time данными
- [Примеры](Examples) - Продвинутые примеры использования
- [Обработка ошибок](Error-Handling) - Детальное руководство по ошибкам

---

**Назад:** [← Установка](Installation) | **Далее:** [REST API →](REST-API)
