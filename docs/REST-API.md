# REST API

Полное руководство по работе с REST API OKX через PHP SDK.

## Обзор

SDK предоставляет доступ ко всем **335 эндпоинтам** OKX v5 API, организованным в 16 категорий.

## Структура API

Все методы доступны через фабричные методы клиента:

```php
$client->account()      // Аккаунт (53 метода)
$client->trade()        // Торговля (32 метода)
$client->market()       // Рыночные данные (24 метода)
$client->asset()        // Активы (26 методов)
$client->publicData()   // Публичные данные (24 метода)
// ... и другие
```

## Account API (53 метода)

### Получение баланса

```php
// Весь баланс
$balance = $client->account()->getBalance();

// Конкретная валюта
$btcBalance = $client->account()->getBalance('BTC');
```

### Конфигурация аккаунта

```php
// Получить конфигурацию
$config = $client->account()->getConfig();

// Установить режим позиций
$client->account()->setPositionMode('long_short_mode');

// Установить кредитное плечо
$client->account()->setLeverage(
    lever: '10',
    mgnMode: 'cross',
    instId: 'BTC-USDT-SWAP'
);
```

### Позиции

```php
// Все позиции
$positions = $client->account()->getPositions();

// Позиции по инструменту
$btcPositions = $client->account()->getPositions(
    instType: 'SWAP',
    instId: 'BTC-USDT-SWAP'
);

// История позиций
$history = $client->account()->getPositionsHistory(
    instType: 'SWAP',
    limit: 100
);
```

### Управление маржей

```php
// Увеличить маржу
$client->account()->increaseDecreaseMargin(
    instId: 'BTC-USDT-SWAP',
    posSide: 'long',
    type: 'add',
    amt: '100'
);

// Уменьшить маржу
$client->account()->increaseDecreaseMargin(
    instId: 'BTC-USDT-SWAP',
    posSide: 'long',
    type: 'reduce',
    amt: '50'
);
```

### Комиссии

```php
$fees = $client->account()->getTradeFee(
    instType: 'SPOT',
    instId: 'BTC-USDT'
);
```

## Trade API (32 метода)

### Размещение ордеров

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

// Limit ордер
$order = $client->trade()->placeOrder(
    instId: 'BTC-USDT',
    tdMode: 'cash',
    side: 'buy',
    ordType: 'limit',
    sz: '0.01',
    px: '50000'
);

// Ордер с TP/SL
$order = $client->trade()->placeOrder(
    instId: 'BTC-USDT',
    tdMode: 'cross',
    side: 'buy',
    ordType: 'limit',
    sz: '0.1',
    px: '50000',
    tpTriggerPx: '55000',
    tpOrdPx: '-1',
    slTriggerPx: '48000',
    slOrdPx: '-1'
);
```

### Пакетные операции

```php
// Пакетное размещение
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

// Пакетная отмена
$result = $client->trade()->cancelBatchOrders([
    ['instId' => 'BTC-USDT', 'ordId' => '123'],
    ['instId' => 'ETH-USDT', 'ordId' => '456']
]);
```

### Управление ордерами

```php
// Получить ордер
$order = $client->trade()->getOrder(
    instId: 'BTC-USDT',
    ordId: '123456789'
);

// Активные ордера
$pending = $client->trade()->getOrdersPending(
    instType: 'SPOT'
);

// История ордеров
$history = $client->trade()->getOrdersHistory(
    instType: 'SPOT',
    limit: 100
);

// Отменить ордер
$result = $client->trade()->cancelOrder(
    instId: 'BTC-USDT',
    ordId: '123456789'
);

// Изменить ордер
$result = $client->trade()->amendOrder(
    instId: 'BTC-USDT',
    ordId: '123456789',
    newSz: '0.02',
    newPx: '51000'
);
```

### Алгоритмические ордера

```php
// Разместить алго-ордер
$algoOrder = $client->trade()->placeAlgoOrder(
    instId: 'BTC-USDT',
    tdMode: 'cash',
    side: 'buy',
    ordType: 'trigger',
    sz: '0.01',
    triggerPx: '49000',
    orderPx: '49000'
);

// Получить алго-ордера
$algoOrders = $client->trade()->getOrdersAlgoPending(
    algoOrdType: 'trigger'
);

// Отменить алго-ордер
$result = $client->trade()->cancelAlgos([
    ['algoId' => '123456']
]);
```

### Исполнения (Fills)

```php
// Последние исполнения
$fills = $client->trade()->getFills(
    instType: 'SPOT',
    limit: 100
);

// История исполнений
$fillsHistory = $client->trade()->getFillsHistory(
    instType: 'SPOT',
    begin: '1609459200000',
    end: '1640995200000'
);
```

## Market API (24 метода)

### Тикеры

```php
// Один инструмент
$ticker = $client->market()->getTicker('BTC-USDT');

// Все инструменты типа
$tickers = $client->market()->getTickers(
    instType: 'SPOT'
);
```

### Стакан (Order Book)

```php
// Стакан
$orderBook = $client->market()->getBooks(
    instId: 'BTC-USDT',
    sz: '20'
);

// Топ 5 уровней
$books5 = $client->market()->getBooksLite('BTC-USDT');
```

### Свечи (Candlesticks)

```php
// Текущие свечи
$candles = $client->market()->getCandles(
    instId: 'BTC-USDT',
    bar: '1H',
    limit: 100
);

// Исторические свечи
$historyCandles = $client->market()->getHistoryCandles(
    instId: 'BTC-USDT',
    bar: '1D',
    after: '1609459200000',
    before: '1640995200000'
);
```

### Сделки

```php
// Последние сделки
$trades = $client->market()->getTrades(
    instId: 'BTC-USDT',
    limit: 100
);

// История сделок
$historyTrades = $client->market()->getHistoryTrades(
    instId: 'BTC-USDT',
    limit: 100
);
```

### Индексы

```php
// Индекс тикеры
$indexTickers = $client->market()->getIndexTickers(
    quoteCcy: 'USDT'
);

// Компоненты индекса
$components = $client->market()->getIndexComponents(
    index: 'BTC-USDT'
);
```

## Asset API (26 методов)

### Балансы

```php
// Все балансы
$balances = $client->asset()->getBalances();

// Конкретная валюта
$btcBalance = $client->asset()->getBalances('BTC');
```

### Переводы

```php
// Перевод между аккаунтами
$transfer = $client->asset()->transfer(
    ccy: 'USDT',
    amt: '100',
    from: '6',    // Funding account
    to: '18',     // Trading account
    type: '0'
);

// Перевод на субаккаунт
$subTransfer = $client->asset()->subaccountTransfer(
    ccy: 'USDT',
    amt: '100',
    from: '6',
    to: '6',
    subAcct: 'sub-account-name'
);
```

### Депозиты

```php
// Адрес депозита
$address = $client->asset()->getDepositAddress('USDT');

// История депозитов
$deposits = $client->asset()->getDepositHistory(
    ccy: 'USDT',
    limit: 100
);
```

### Выводы

```php
// Вывод средств
$withdrawal = $client->asset()->withdrawal(
    ccy: 'USDT',
    amt: '100',
    dest: '4',
    toAddr: 'your-wallet-address',
    fee: '1',
    chain: 'USDT-TRC20'
);

// История выводов
$withdrawals = $client->asset()->getWithdrawalHistory(
    ccy: 'USDT',
    limit: 100
);

// Отменить вывод
$cancel = $client->asset()->cancelWithdrawal('withdrawal-id');
```

### Конвертация

```php
// Оценка конвертации
$quote = $client->asset()->convertEstimateQuote(
    baseCcy: 'BTC',
    quoteCcy: 'USDT',
    side: 'sell',
    rfqSz: '0.1',
    rfqSzCcy: 'BTC'
);

// Выполнить конвертацию
$trade = $client->asset()->convertTrade(
    quoteId: $quote[0]['quoteId'],
    baseCcy: 'BTC',
    quoteCcy: 'USDT',
    side: 'sell',
    sz: '0.1',
    szCcy: 'BTC'
);
```

## Public Data API (24 метода)

### Инструменты

```php
// Все инструменты
$instruments = $client->publicData()->getInstruments(
    instType: 'SPOT'
);

// Конкретный инструмент
$instrument = $client->publicData()->getInstruments(
    instType: 'SPOT',
    instId: 'BTC-USDT'
);
```

### Funding Rate

```php
// Текущий funding rate
$fundingRate = $client->publicData()->getFundingRate('BTC-USDT-SWAP');

// История funding rate
$history = $client->publicData()->getFundingRateHistory(
    instId: 'BTC-USDT-SWAP',
    limit: 100
);
```

### Open Interest

```php
$openInterest = $client->publicData()->getOpenInterest(
    instType: 'SWAP',
    uly: 'BTC-USDT'
);
```

### Лимиты позиций

```php
$tiers = $client->publicData()->getPositionTiers(
    instType: 'SWAP',
    tdMode: 'cross',
    uly: 'BTC-USDT'
);
```

### Время сервера

```php
$time = $client->publicData()->getTime();
echo "Время сервера: " . $time[0]['ts'];
```

## Finance API (33 метода)

### Savings

```php
// Баланс в savings
$balance = $client->finance()->getSavingsBalance();

// Purchase/Redeem
$result = $client->finance()->savingsPurchaseRedempt(
    ccy: 'USDT',
    amt: '100',
    side: 'purchase'
);
```

### Staking

```php
// Доступные предложения
$offers = $client->finance()->getStakingDefiOffers(
    productId: 'BTC-staking'
);

// Stake
$stake = $client->finance()->purchaseStakingDefi(
    productId: 'BTC-staking',
    investData: 'BTC',
    amt: '0.1'
);
```

## Полный список категорий

| Категория | Методы | Описание |
|-----------|--------|----------|
| `account()` | 53 | Управление аккаунтом |
| `trade()` | 32 | Торговые операции |
| `market()` | 24 | Рыночные данные |
| `publicData()` | 24 | Публичные данные |
| `asset()` | 26 | Управление активами |
| `finance()` | 33 | Финансовые продукты |
| `copyTrading()` | 26 | Копи-трейдинг |
| `tradingBot()` | 44 | Торговые боты |
| `rfq()` | 20 | Блочная торговля |
| `sprd()` | 13 | Спред-трейдинг |
| `rubik()` | 15 | Торговая статистика |
| `fiat()` | 13 | Фиатные операции |
| `users()` | 8 | Субаккаунты |
| `support()` | 2 | Объявления |
| `systemStatus()` | 1 | Статус системы |
| `affiliate()` | 1 | Партнерская программа |

## Общие параметры

### Типы инструментов (instType)

- `SPOT` - Спот
- `MARGIN` - Маржинальная торговля
- `SWAP` - Бессрочные свопы
- `FUTURES` - Фьючерсы
- `OPTION` - Опционы

### Режимы торговли (tdMode)

- `cash` - Спот
- `cross` - Кросс-маржа
- `isolated` - Изолированная маржа

### Стороны (side)

- `buy` - Покупка
- `sell` - Продажа

### Типы ордеров (ordType)

- `market` - Рыночный
- `limit` - Лимитный
- `post_only` - Post-only
- `fok` - Fill-or-Kill
- `ioc` - Immediate-or-Cancel

## Пагинация

Многие методы поддерживают пагинацию:

```php
// Первая страница
$orders = $client->trade()->getOrdersHistory(
    instType: 'SPOT',
    limit: 100
);

// Следующая страница
$nextOrders = $client->trade()->getOrdersHistory(
    instType: 'SPOT',
    after: $orders[count($orders) - 1]['ordId'],
    limit: 100
);
```

---

**Назад:** [← Быстрый старт](Quick-Start) | **Далее:** [WebSocket API →](WebSocket-API)
