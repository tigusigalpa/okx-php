# WebSocket API

Руководство по работе с WebSocket API OKX для получения данных в реальном времени.

## Обзор

SDK поддерживает все **53 WebSocket канала** OKX:
- **Публичные каналы** - Рыночные данные (не требуют аутентификации)
- **Приватные каналы** - Данные аккаунта (требуют аутентификации)
- **Бизнес каналы** - Депозиты, выводы и т.д.

## Создание клиента

```php
use Tigusigalpa\OKX\WebsocketClient;

$ws = new WebsocketClient(
    apiKey: 'your-api-key',
    secretKey: 'your-secret-key',
    passphrase: 'your-passphrase',
    isDemo: false
);
```

## Публичные каналы

### Подключение

```php
$ws->connectPublic();
```

### Тикеры

```php
$ws->subscribe('tickers', ['instId' => 'BTC-USDT'], function ($data) {
    $ticker = $data['data'][0];
    echo "BTC-USDT: {$ticker['last']}\n";
    echo "24h Volume: {$ticker['vol24h']}\n";
    echo "24h Change: {$ticker['sodUtc0']}\n";
});
```

### Стакан (Order Book)

```php
// Полный стакан
$ws->subscribe('books', ['instId' => 'BTC-USDT'], function ($data) {
    $book = $data['data'][0];
    echo "Asks: " . count($book['asks']) . "\n";
    echo "Bids: " . count($book['bids']) . "\n";
});

// Топ 5 уровней
$ws->subscribe('books5', ['instId' => 'BTC-USDT'], function ($data) {
    $book = $data['data'][0];
    $bestAsk = $book['asks'][0];
    $bestBid = $book['bids'][0];
    echo "Best Ask: {$bestAsk[0]} @ {$bestAsk[1]}\n";
    echo "Best Bid: {$bestBid[0]} @ {$bestBid[1]}\n";
});
```

### Сделки

```php
$ws->subscribe('trades', ['instId' => 'BTC-USDT'], function ($data) {
    foreach ($data['data'] as $trade) {
        echo "Trade: {$trade['side']} {$trade['sz']} @ {$trade['px']}\n";
    }
});
```

### Свечи (Candlesticks)

```php
// Доступные интервалы: 1m, 3m, 5m, 15m, 30m, 1H, 2H, 4H, 6H, 12H, 1D, 1W, 1M
$ws->subscribe('candle1H', ['instId' => 'BTC-USDT'], function ($data) {
    foreach ($data['data'] as $candle) {
        [$ts, $open, $high, $low, $close, $vol, $volCcy] = $candle;
        echo "Candle: O:{$open} H:{$high} L:{$low} C:{$close}\n";
    }
});
```

### Индексы

```php
$ws->subscribe('index-tickers', ['instId' => 'BTC-USDT'], function ($data) {
    $index = $data['data'][0];
    echo "Index Price: {$index['idxPx']}\n";
});
```

### Funding Rate

```php
$ws->subscribe('funding-rate', ['instId' => 'BTC-USDT-SWAP'], function ($data) {
    $funding = $data['data'][0];
    echo "Funding Rate: {$funding['fundingRate']}\n";
    echo "Next Funding Time: {$funding['nextFundingTime']}\n";
});
```

### Mark Price

```php
$ws->subscribe('mark-price', ['instId' => 'BTC-USDT-SWAP'], function ($data) {
    $mark = $data['data'][0];
    echo "Mark Price: {$mark['markPx']}\n";
});
```

### Open Interest

```php
$ws->subscribe('open-interest', ['instId' => 'BTC-USDT-SWAP'], function ($data) {
    $oi = $data['data'][0];
    echo "Open Interest: {$oi['oi']}\n";
});
```

## Приватные каналы

### Подключение

```php
$ws->connectPrivate();
```

### Аккаунт

```php
$ws->subscribe('account', ['ccy' => 'BTC'], function ($data) {
    foreach ($data['data'] as $account) {
        echo "Currency: {$account['ccy']}\n";
        echo "Balance: {$account['bal']}\n";
        echo "Available: {$account['availBal']}\n";
        echo "Frozen: {$account['frozenBal']}\n";
    }
});
```

### Позиции

```php
$ws->subscribe('positions', [
    'instType' => 'SWAP',
    'instId' => 'BTC-USDT-SWAP'
], function ($data) {
    foreach ($data['data'] as $position) {
        echo "Position: {$position['instId']}\n";
        echo "Size: {$position['pos']}\n";
        echo "PnL: {$position['upl']}\n";
        echo "Margin: {$position['margin']}\n";
    }
});
```

### Ордера

```php
$ws->subscribe('orders', ['instType' => 'SPOT'], function ($data) {
    foreach ($data['data'] as $order) {
        echo "Order {$order['ordId']}: {$order['state']}\n";
        echo "Instrument: {$order['instId']}\n";
        echo "Side: {$order['side']}\n";
        echo "Price: {$order['px']}\n";
        echo "Size: {$order['sz']}\n";
        echo "Filled: {$order['accFillSz']}\n";
    }
});
```

### Алгоритмические ордера

```php
$ws->subscribe('orders-algo', ['instType' => 'SPOT'], function ($data) {
    foreach ($data['data'] as $algoOrder) {
        echo "Algo Order {$algoOrder['algoId']}: {$algoOrder['state']}\n";
        echo "Type: {$algoOrder['ordType']}\n";
        echo "Trigger Price: {$algoOrder['triggerPx']}\n";
    }
});
```

### Исполнения (Fills)

```php
$ws->subscribe('fills', ['instType' => 'SPOT'], function ($data) {
    foreach ($data['data'] as $fill) {
        echo "Fill: {$fill['side']} {$fill['fillSz']} @ {$fill['fillPx']}\n";
        echo "Fee: {$fill['fee']} {$fill['feeCcy']}\n";
    }
});
```

### Баланс и позиции

```php
$ws->subscribe('balance_and_position', [], function ($data) {
    $balData = $data['data'][0]['balData'] ?? [];
    $posData = $data['data'][0]['posData'] ?? [];
    
    echo "Balances:\n";
    foreach ($balData as $bal) {
        echo "  {$bal['ccy']}: {$bal['cashBal']}\n";
    }
    
    echo "Positions:\n";
    foreach ($posData as $pos) {
        echo "  {$pos['instId']}: {$pos['pos']}\n";
    }
});
```

### Предупреждения о ликвидации

```php
$ws->subscribe('liquidation-warning', ['instType' => 'SWAP'], function ($data) {
    foreach ($data['data'] as $warning) {
        echo "⚠️ Liquidation Warning!\n";
        echo "Position: {$warning['instId']}\n";
        echo "Margin Ratio: {$warning['mgnRatio']}\n";
    }
});
```

## Бизнес каналы

### Подключение

```php
$ws->connectBusiness();
```

### Депозиты

```php
$ws->subscribe('deposit-info', [], function ($data) {
    foreach ($data['data'] as $deposit) {
        echo "Deposit: {$deposit['ccy']} {$deposit['amt']}\n";
        echo "Status: {$deposit['state']}\n";
        echo "TxId: {$deposit['txId']}\n";
    }
});
```

### Выводы

```php
$ws->subscribe('withdrawal-info', [], function ($data) {
    foreach ($data['data'] as $withdrawal) {
        echo "Withdrawal: {$withdrawal['ccy']} {$withdrawal['amt']}\n";
        echo "Status: {$withdrawal['state']}\n";
        echo "TxId: {$withdrawal['txId']}\n";
    }
});
```

## Управление подписками

### Отписка от канала

```php
$ws->unsubscribe('tickers', ['instId' => 'BTC-USDT']);
```

### Множественные подписки

```php
// Подписаться на несколько инструментов
$instruments = ['BTC-USDT', 'ETH-USDT', 'SOL-USDT'];

foreach ($instruments as $instId) {
    $ws->subscribe('tickers', ['instId' => $instId], function ($data) use ($instId) {
        $ticker = $data['data'][0];
        echo "{$instId}: {$ticker['last']}\n";
    });
}
```

## Запуск клиента

```php
// Запустить прослушивание (блокирующий вызов)
$ws->run();

// Остановить клиент
$ws->stop();
```

## Автоматическое переподключение

Клиент автоматически переподключается при разрыве соединения:

```php
$ws->connectPublic();

$ws->subscribe('tickers', ['instId' => 'BTC-USDT'], function ($data) {
    // Подписка автоматически восстанавливается при переподключении
    echo "Price: {$data['data'][0]['last']}\n";
});

$ws->run(); // Автоматически переподключается при ошибках
```

## Heartbeat (Ping/Pong)

Клиент автоматически отправляет ping каждые 25 секунд:

```php
// Автоматически обрабатывается внутри клиента
// Не требует ручной настройки
```

## Полный пример

```php
use Tigusigalpa\OKX\WebsocketClient;

$ws = new WebsocketClient(
    apiKey: 'your-api-key',
    secretKey: 'your-secret-key',
    passphrase: 'your-passphrase'
);

// Публичные данные
$ws->connectPublic();

// Мониторинг цены
$ws->subscribe('tickers', ['instId' => 'BTC-USDT'], function ($data) {
    $ticker = $data['data'][0];
    $price = (float) $ticker['last'];
    
    // Алерт при достижении цены
    if ($price > 60000) {
        echo "🚀 BTC выше $60,000!\n";
    }
});

// Мониторинг сделок
$ws->subscribe('trades', ['instId' => 'BTC-USDT'], function ($data) {
    foreach ($data['data'] as $trade) {
        $size = (float) $trade['sz'];
        
        // Алерт на крупные сделки
        if ($size > 10) {
            echo "🐋 Крупная сделка: {$trade['side']} {$size} BTC\n";
        }
    }
});

// Запустить
$ws->run();
```

## Список всех каналов

### Публичные каналы (31)

- `tickers` - Тикеры
- `books` - Полный стакан
- `books5` - Топ 5 уровней
- `books-l2-tbt` - L2 стакан
- `bbo-tbt` - Best bid/offer
- `trades` - Сделки
- `candle1m`, `candle3m`, `candle5m`, `candle15m`, `candle30m` - Свечи (минуты)
- `candle1H`, `candle2H`, `candle4H`, `candle6H`, `candle12H` - Свечи (часы)
- `candle1D`, `candle1W`, `candle1M` - Свечи (день/неделя/месяц)
- `index-tickers` - Индекс тикеры
- `index-candle1D` - Индекс свечи
- `mark-price` - Mark price
- `mark-price-candle1D` - Mark price свечи
- `price-limit` - Лимиты цен
- `funding-rate` - Funding rate
- `open-interest` - Open interest
- `estimated-price` - Расчетная цена
- `opt-summary` - Опционы сводка
- `liquidation-orders` - Ликвидации
- `public-block-trades` - Блочные сделки
- `block-tickers` - Блочные тикеры

### Приватные каналы (18)

- `account` - Аккаунт
- `positions` - Позиции
- `balance_and_position` - Баланс и позиции
- `orders` - Ордера
- `orders-algo` - Алго-ордера
- `fills` - Исполнения
- `liquidation-warning` - Предупреждения о ликвидации
- `account-greeks` - Greeks
- `rfqs` - RFQ
- `quotes` - Котировки
- `grid-orders-spot` - Grid ордера (спот)
- `grid-orders-contract` - Grid ордера (контракты)
- `grid-positions` - Grid позиции
- `grid-sub-orders` - Grid суб-ордера
- `algo-advance` - Продвинутые алго
- `algo-recurring-buy` - Рекуррентная покупка
- `copytrading-lead-notification` - Копи-трейдинг уведомления
- `adl-warning` - ADL предупреждения

### Бизнес каналы (4)

- `deposit-info` - Депозиты
- `withdrawal-info` - Выводы
- `sprd-orders` - Спред ордера
- `sprd-trades` - Спред сделки

---

**Назад:** [← REST API](REST-API) | **Далее:** [Laravel Integration →](Laravel-Integration)
