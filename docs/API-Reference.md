# API Reference

Полный справочник методов OKX PHP SDK.

## Client

Основной класс для работы с OKX API.

### Конструктор

```php
public function __construct(
    string $apiKey,
    string $secretKey,
    string $passphrase,
    bool $isDemo = false,
    string $baseUrl = 'https://www.okx.com',
    ?HttpClient $httpClient = null,
    ?LoggerInterface $logger = null
)
```

**Параметры:**
- `$apiKey` - API ключ OKX
- `$secretKey` - Секретный ключ
- `$passphrase` - Passphrase
- `$isDemo` - Режим demo торговли
- `$baseUrl` - Базовый URL API
- `$httpClient` - Опциональный HTTP клиент (Guzzle)
- `$logger` - Опциональный PSR-3 логгер

### Фабричные методы

```php
$client->account()      // Account API
$client->trade()        // Trade API
$client->market()       // Market API
$client->publicData()   // Public Data API
$client->asset()        // Asset API
$client->finance()      // Finance API
$client->copyTrading()  // Copy Trading API
$client->tradingBot()   // Trading Bot API
$client->rfq()          // RFQ API
$client->sprd()         // Spread Trading API
$client->rubik()        // Trading Statistics API
$client->fiat()         // Fiat API
$client->users()        // Users API
$client->support()      // Support API
$client->systemStatus() // System Status API
$client->affiliate()    // Affiliate API
```

## Account API

### getBalance

Получить баланс аккаунта.

```php
public function getBalance(?string $ccy = null): array
```

**Параметры:**
- `$ccy` - Валюта (опционально)

**Возвращает:** Массив с балансом

**Пример:**
```php
$balance = $client->account()->getBalance();
$btcBalance = $client->account()->getBalance('BTC');
```

### getPositions

Получить позиции.

```php
public function getPositions(
    ?string $instType = null,
    ?string $instId = null,
    ?string $posId = null
): array
```

**Параметры:**
- `$instType` - Тип инструмента (SPOT, SWAP, FUTURES, OPTION)
- `$instId` - ID инструмента
- `$posId` - ID позиции

### setLeverage

Установить кредитное плечо.

```php
public function setLeverage(
    string $lever,
    ?string $mgnMode = null,
    ?string $instId = null,
    ?string $ccy = null,
    ?string $posSide = null
): array
```

**Параметры:**
- `$lever` - Размер плеча (например, "10")
- `$mgnMode` - Режим маржи (cross, isolated)
- `$instId` - ID инструмента
- `$ccy` - Валюта
- `$posSide` - Сторона позиции (long, short)

## Trade API

### placeOrder

Разместить ордер.

```php
public function placeOrder(
    string $instId,
    string $tdMode,
    string $side,
    string $ordType,
    string $sz,
    ?string $px = null,
    ?string $ccy = null,
    ?string $clOrdId = null,
    ?string $tag = null,
    ?string $posSide = null,
    ?bool $reduceOnly = null,
    ?string $tgtCcy = null,
    ?bool $banAmend = null,
    ?string $tpTriggerPx = null,
    ?string $tpOrdPx = null,
    ?string $slTriggerPx = null,
    ?string $slOrdPx = null,
    ?string $tpTriggerPxType = null,
    ?string $slTriggerPxType = null,
    ?string $quickMgnType = null,
    ?string $stpId = null,
    ?string $stpMode = null,
    ?array $attachAlgoOrds = null
): array
```

**Обязательные параметры:**
- `$instId` - ID инструмента (например, "BTC-USDT")
- `$tdMode` - Режим торговли (cash, cross, isolated)
- `$side` - Сторона (buy, sell)
- `$ordType` - Тип ордера (market, limit, post_only, fok, ioc)
- `$sz` - Размер ордера

**Опциональные параметры:**
- `$px` - Цена (для limit ордеров)
- `$tgtCcy` - Целевая валюта (base_ccy, quote_ccy)
- `$tpTriggerPx` - Цена триггера Take Profit
- `$slTriggerPx` - Цена триггера Stop Loss

**Пример:**
```php
// Market ордер
$order = $client->trade()->placeOrder(
    instId: 'BTC-USDT',
    tdMode: 'cash',
    side: 'buy',
    ordType: 'market',
    sz: '100',
    tgtCcy: 'quote_ccy'
);

// Limit ордер с TP/SL
$order = $client->trade()->placeOrder(
    instId: 'BTC-USDT',
    tdMode: 'cross',
    side: 'buy',
    ordType: 'limit',
    sz: '0.1',
    px: '50000',
    tpTriggerPx: '55000',
    slTriggerPx: '48000'
);
```

### cancelOrder

Отменить ордер.

```php
public function cancelOrder(
    string $instId,
    ?string $ordId = null,
    ?string $clOrdId = null
): array
```

**Параметры:**
- `$instId` - ID инструмента
- `$ordId` - ID ордера (один из ordId или clOrdId обязателен)
- `$clOrdId` - Client Order ID

### getOrdersPending

Получить активные ордера.

```php
public function getOrdersPending(
    ?string $instType = null,
    ?string $uly = null,
    ?string $instId = null,
    ?string $ordType = null,
    ?string $state = null,
    ?string $after = null,
    ?string $before = null,
    ?int $limit = null,
    ?string $instFamily = null
): array
```

### getFills

Получить исполнения.

```php
public function getFills(
    ?string $instType = null,
    ?string $uly = null,
    ?string $instId = null,
    ?string $ordId = null,
    ?string $after = null,
    ?string $before = null,
    ?string $begin = null,
    ?string $end = null,
    ?int $limit = null,
    ?string $instFamily = null
): array
```

## Market API

### getTicker

Получить тикер.

```php
public function getTicker(string $instId): array
```

**Параметры:**
- `$instId` - ID инструмента

**Возвращает:**
```php
[
    [
        'instId' => 'BTC-USDT',
        'last' => '50000',
        'lastSz' => '0.1',
        'askPx' => '50001',
        'bidPx' => '49999',
        'open24h' => '49000',
        'high24h' => '51000',
        'low24h' => '48000',
        'vol24h' => '1000',
        'volCcy24h' => '50000000'
    ]
]
```

### getBooks

Получить стакан ордеров.

```php
public function getBooks(
    string $instId,
    ?string $sz = null
): array
```

**Параметры:**
- `$instId` - ID инструмента
- `$sz` - Глубина стакана (1-400)

### getCandles

Получить свечи.

```php
public function getCandles(
    string $instId,
    ?string $bar = null,
    ?string $after = null,
    ?string $before = null,
    ?int $limit = null
): array
```

**Параметры:**
- `$instId` - ID инструмента
- `$bar` - Интервал (1m, 3m, 5m, 15m, 30m, 1H, 2H, 4H, 6H, 12H, 1D, 1W, 1M)
- `$after` - Timestamp после
- `$before` - Timestamp до
- `$limit` - Количество свечей (макс 300)

**Возвращает:**
```php
[
    [timestamp, open, high, low, close, volume, volumeCcy],
    ...
]
```

### getTrades

Получить последние сделки.

```php
public function getTrades(
    string $instId,
    ?int $limit = null
): array
```

## Asset API

### transfer

Перевод между аккаунтами.

```php
public function transfer(
    string $ccy,
    string $amt,
    string $from,
    string $to,
    ?string $type = null,
    ?string $subAcct = null,
    ?string $instId = null,
    ?string $toInstId = null,
    ?bool $loanTrans = null,
    ?string $clientId = null,
    ?bool $omitPosRisk = null
): array
```

**Параметры:**
- `$ccy` - Валюта
- `$amt` - Сумма
- `$from` - Откуда (6: Funding, 18: Trading)
- `$to` - Куда (6: Funding, 18: Trading)

### withdrawal

Вывод средств.

```php
public function withdrawal(
    string $ccy,
    string $amt,
    string $dest,
    string $toAddr,
    string $fee,
    ?string $chain = null,
    ?string $areaCode = null,
    ?string $clientId = null
): array
```

**Параметры:**
- `$ccy` - Валюта
- `$amt` - Сумма
- `$dest` - Назначение (3: internal, 4: on-chain)
- `$toAddr` - Адрес получателя
- `$fee` - Комиссия
- `$chain` - Сеть (например, "USDT-TRC20")

## WebSocket Client

### Конструктор

```php
public function __construct(
    string $apiKey,
    string $secretKey,
    string $passphrase,
    bool $isDemo = false,
    ?LoggerInterface $logger = null
)
```

### connectPublic

Подключиться к публичным каналам.

```php
public function connectPublic(): void
```

### connectPrivate

Подключиться к приватным каналам.

```php
public function connectPrivate(): void
```

### connectBusiness

Подключиться к бизнес каналам.

```php
public function connectBusiness(): void
```

### subscribe

Подписаться на канал.

```php
public function subscribe(
    string $channel,
    array $args,
    callable $callback
): void
```

**Параметры:**
- `$channel` - Название канала
- `$args` - Аргументы канала
- `$callback` - Callback функция для обработки данных

**Пример:**
```php
$ws->subscribe('tickers', ['instId' => 'BTC-USDT'], function ($data) {
    echo "Price: " . $data['data'][0]['last'];
});
```

### unsubscribe

Отписаться от канала.

```php
public function unsubscribe(string $channel, array $args): void
```

### run

Запустить прослушивание.

```php
public function run(): void
```

### stop

Остановить клиент.

```php
public function stop(): void
```

## Exceptions

### OKXException

Базовое исключение.

**Свойства:**
- `$okxCode` - Код ошибки OKX
- `$rawResponse` - Сырой ответ от API

### AuthenticationException

Ошибки аутентификации (50111, 50113).

### RateLimitException

Превышение лимита запросов (50011).

### InvalidParameterException

Неверные параметры (51000-51099).

### InsufficientFundsException

Недостаточно средств (54000-54099).

## Константы

### Типы инструментов

- `SPOT` - Спот
- `MARGIN` - Маржинальная торговля
- `SWAP` - Бессрочные свопы
- `FUTURES` - Фьючерсы
- `OPTION` - Опционы

### Режимы торговли

- `cash` - Спот
- `cross` - Кросс-маржа
- `isolated` - Изолированная маржа

### Типы ордеров

- `market` - Рыночный
- `limit` - Лимитный
- `post_only` - Post-only
- `fok` - Fill-or-Kill
- `ioc` - Immediate-or-Cancel
- `optimal_limit_ioc` - Optimal limit IOC

### Стороны

- `buy` - Покупка
- `sell` - Продажа

### Стороны позиций

- `long` - Длинная позиция
- `short` - Короткая позиция
- `net` - Нетто (односторонний режим)

### Интервалы свечей

- `1m`, `3m`, `5m`, `15m`, `30m` - Минуты
- `1H`, `2H`, `4H`, `6H`, `12H` - Часы
- `1D` - День
- `1W` - Неделя
- `1M` - Месяц

## Полный список методов

### Account (53 метода)

- `getAccountLevel()`
- `getBalance(?string $ccy)`
- `getBills(...)`
- `getConfig()`
- `getPositions(...)`
- `setLeverage(...)`
- `setPositionMode(string $posMode)`
- `increaseDecreaseMargin(...)`
- И другие...

### Trade (32 метода)

- `placeOrder(...)`
- `batchOrders(array $orders)`
- `cancelOrder(...)`
- `cancelBatchOrders(...)`
- `amendOrder(...)`
- `getOrder(...)`
- `getOrdersPending(...)`
- `getOrdersHistory(...)`
- `getFills(...)`
- `placeAlgoOrder(...)`
- И другие...

### Market (24 метода)

- `getTicker(string $instId)`
- `getTickers(...)`
- `getBooks(...)`
- `getCandles(...)`
- `getTrades(...)`
- `getHistoryCandles(...)`
- И другие...

### Asset (26 методов)

- `getBalances(?string $ccy)`
- `transfer(...)`
- `withdrawal(...)`
- `getDepositAddress(string $ccy)`
- `getDepositHistory(...)`
- `getWithdrawalHistory(...)`
- И другие...

### Public Data (24 метода)

- `getInstruments(...)`
- `getTime()`
- `getFundingRate(string $instId)`
- `getOpenInterest(...)`
- `getPositionTiers(...)`
- И другие...

## Типы возвращаемых данных

Все методы возвращают массивы с данными от OKX API. Структура ответа:

```php
[
    [
        // Данные первого элемента
    ],
    [
        // Данные второго элемента
    ],
    ...
]
```

Для получения типизированных данных можно использовать DTOs:

```php
use Tigusigalpa\OKX\DTO\Trade\PlaceOrderResponse;

$order = $client->trade()->placeOrder(...);
$orderDto = PlaceOrderResponse::fromArray($order[0]);

echo $orderDto->ordId;
echo $orderDto->sCode;
```

---

**Назад:** [← Testing](Testing) | **Начало:** [Home](Home)
