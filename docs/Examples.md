# Примеры использования

Продвинутые примеры использования OKX PHP SDK.

## Торговые стратегии

### Grid Trading Bot

```php
class GridTradingBot
{
    public function __construct(
        private Client $okx,
        private string $symbol,
        private float $lowerPrice,
        private float $upperPrice,
        private int $gridLevels,
        private float $totalInvestment
    ) {}
    
    public function createGrid(): array
    {
        $priceStep = ($this->upperPrice - $this->lowerPrice) / ($this->gridLevels - 1);
        $amountPerLevel = $this->totalInvestment / $this->gridLevels;
        
        $orders = [];
        
        for ($i = 0; $i < $this->gridLevels; $i++) {
            $price = $this->lowerPrice + ($priceStep * $i);
            
            // Размещаем buy ордера
            $buyOrder = $this->okx->trade()->placeOrder(
                instId: $this->symbol,
                tdMode: 'cash',
                side: 'buy',
                ordType: 'limit',
                sz: (string)($amountPerLevel / $price),
                px: (string)$price
            );
            
            $orders[] = $buyOrder;
            
            // Размещаем sell ордера чуть выше
            $sellPrice = $price + $priceStep;
            $sellOrder = $this->okx->trade()->placeOrder(
                instId: $this->symbol,
                tdMode: 'cash',
                side: 'sell',
                ordType: 'limit',
                sz: (string)($amountPerLevel / $price),
                px: (string)$sellPrice
            );
            
            $orders[] = $sellOrder;
        }
        
        return $orders;
    }
}

// Использование
$bot = new GridTradingBot(
    okx: $client,
    symbol: 'BTC-USDT',
    lowerPrice: 45000,
    upperPrice: 55000,
    gridLevels: 10,
    totalInvestment: 10000
);

$orders = $bot->createGrid();
```

### DCA (Dollar Cost Averaging)

```php
class DCAStrategy
{
    public function __construct(
        private Client $okx,
        private string $symbol,
        private float $amountPerPurchase,
        private string $interval // 'daily', 'weekly', 'monthly'
    ) {}
    
    public function execute(): array
    {
        // Получить текущую цену
        $ticker = $this->okx->market()->getTicker($this->symbol);
        $currentPrice = (float)$ticker[0]['last'];
        
        // Рассчитать количество
        $quantity = $this->amountPerPurchase / $currentPrice;
        
        // Разместить market ордер
        $order = $this->okx->trade()->placeOrder(
            instId: $this->symbol,
            tdMode: 'cash',
            side: 'buy',
            ordType: 'market',
            sz: (string)$this->amountPerPurchase,
            tgtCcy: 'quote_ccy'
        );
        
        Log::info('DCA purchase executed', [
            'symbol' => $this->symbol,
            'amount' => $this->amountPerPurchase,
            'price' => $currentPrice,
            'quantity' => $quantity
        ]);
        
        return $order;
    }
}

// Laravel Scheduler
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        $dca = new DCAStrategy(
            okx: app(Client::class),
            symbol: 'BTC-USDT',
            amountPerPurchase: 100,
            interval: 'daily'
        );
        $dca->execute();
    })->daily();
}
```

### Stop Loss / Take Profit Manager

```php
class PositionManager
{
    public function __construct(private Client $okx) {}
    
    public function setStopLossAndTakeProfit(
        string $instId,
        float $entryPrice,
        float $stopLossPercent,
        float $takeProfitPercent
    ): array {
        $stopLossPrice = $entryPrice * (1 - $stopLossPercent / 100);
        $takeProfitPrice = $entryPrice * (1 + $takeProfitPercent / 100);
        
        // Получить текущую позицию
        $positions = $this->okx->account()->getPositions(
            instId: $instId
        );
        
        if (empty($positions)) {
            throw new \Exception('No position found');
        }
        
        $position = $positions[0];
        $size = $position['pos'];
        
        // Установить TP/SL через алго-ордер
        $algoOrder = $this->okx->trade()->placeAlgoOrder(
            instId: $instId,
            tdMode: 'cross',
            side: $size > 0 ? 'sell' : 'buy',
            ordType: 'oco',
            sz: abs($size),
            tpTriggerPx: (string)$takeProfitPrice,
            tpOrdPx: '-1',
            slTriggerPx: (string)$stopLossPrice,
            slOrdPx: '-1'
        );
        
        return $algoOrder;
    }
}
```

## Мониторинг и алерты

### Price Alert System

```php
class PriceAlertSystem
{
    private array $alerts = [];
    
    public function __construct(private WebsocketClient $ws) {}
    
    public function addAlert(
        string $symbol,
        float $targetPrice,
        string $direction, // 'above' or 'below'
        callable $callback
    ): void {
        $this->alerts[] = [
            'symbol' => $symbol,
            'target' => $targetPrice,
            'direction' => $direction,
            'callback' => $callback
        ];
    }
    
    public function start(): void
    {
        $this->ws->connectPublic();
        
        // Подписаться на все символы
        $symbols = array_unique(array_column($this->alerts, 'symbol'));
        
        foreach ($symbols as $symbol) {
            $this->ws->subscribe('tickers', ['instId' => $symbol], 
                function ($data) {
                    $this->checkAlerts($data);
                }
            );
        }
        
        $this->ws->run();
    }
    
    private function checkAlerts(array $data): void
    {
        $ticker = $data['data'][0];
        $symbol = $ticker['instId'];
        $currentPrice = (float)$ticker['last'];
        
        foreach ($this->alerts as $key => $alert) {
            if ($alert['symbol'] !== $symbol) {
                continue;
            }
            
            $triggered = match($alert['direction']) {
                'above' => $currentPrice >= $alert['target'],
                'below' => $currentPrice <= $alert['target'],
            };
            
            if ($triggered) {
                ($alert['callback'])($symbol, $currentPrice, $alert['target']);
                unset($this->alerts[$key]);
            }
        }
    }
}

// Использование
$alertSystem = new PriceAlertSystem($wsClient);

$alertSystem->addAlert(
    symbol: 'BTC-USDT',
    targetPrice: 60000,
    direction: 'above',
    callback: function ($symbol, $current, $target) {
        Mail::to('trader@example.com')->send(
            new PriceAlertMail($symbol, $current, $target)
        );
    }
);

$alertSystem->start();
```

### Volume Spike Detector

```php
class VolumeSpikeDetector
{
    private array $volumeHistory = [];
    
    public function __construct(
        private WebsocketClient $ws,
        private float $spikeThreshold = 2.0 // 2x средний объем
    ) {}
    
    public function monitor(string $symbol): void
    {
        $this->ws->connectPublic();
        
        $this->ws->subscribe('trades', ['instId' => $symbol], 
            function ($data) use ($symbol) {
                foreach ($data['data'] as $trade) {
                    $this->analyzeVolume($symbol, (float)$trade['sz']);
                }
            }
        );
        
        $this->ws->run();
    }
    
    private function analyzeVolume(string $symbol, float $volume): void
    {
        if (!isset($this->volumeHistory[$symbol])) {
            $this->volumeHistory[$symbol] = [];
        }
        
        $this->volumeHistory[$symbol][] = $volume;
        
        // Хранить последние 100 сделок
        if (count($this->volumeHistory[$symbol]) > 100) {
            array_shift($this->volumeHistory[$symbol]);
        }
        
        // Рассчитать средний объем
        $avgVolume = array_sum($this->volumeHistory[$symbol]) / 
                     count($this->volumeHistory[$symbol]);
        
        // Проверить на спайк
        if ($volume > $avgVolume * $this->spikeThreshold) {
            $this->onVolumeSpike($symbol, $volume, $avgVolume);
        }
    }
    
    private function onVolumeSpike(string $symbol, float $volume, float $avg): void
    {
        Log::warning('Volume spike detected', [
            'symbol' => $symbol,
            'volume' => $volume,
            'average' => $avg,
            'multiplier' => $volume / $avg
        ]);
        
        // Отправить уведомление
        event(new VolumeSpikeDetected($symbol, $volume, $avg));
    }
}
```

## Портфолио менеджмент

### Portfolio Tracker

```php
class PortfolioTracker
{
    public function __construct(private Client $okx) {}
    
    public function getPortfolioValue(): array
    {
        $balance = $this->okx->account()->getBalance();
        $details = $balance[0]['details'];
        
        $portfolio = [];
        $totalValueUSD = 0;
        
        foreach ($details as $asset) {
            if ((float)$asset['bal'] == 0) {
                continue;
            }
            
            $ccy = $asset['ccy'];
            $amount = (float)$asset['bal'];
            
            // Получить цену в USDT
            $priceUSD = $this->getPriceInUSD($ccy);
            $valueUSD = $amount * $priceUSD;
            
            $portfolio[] = [
                'currency' => $ccy,
                'amount' => $amount,
                'price_usd' => $priceUSD,
                'value_usd' => $valueUSD
            ];
            
            $totalValueUSD += $valueUSD;
        }
        
        // Добавить процентное соотношение
        foreach ($portfolio as &$asset) {
            $asset['percentage'] = ($asset['value_usd'] / $totalValueUSD) * 100;
        }
        
        return [
            'total_value_usd' => $totalValueUSD,
            'assets' => $portfolio,
            'timestamp' => now()
        ];
    }
    
    private function getPriceInUSD(string $ccy): float
    {
        if ($ccy === 'USDT' || $ccy === 'USD') {
            return 1.0;
        }
        
        try {
            $ticker = $this->okx->market()->getTicker("{$ccy}-USDT");
            return (float)$ticker[0]['last'];
        } catch (\Exception $e) {
            Log::warning("Could not get price for {$ccy}");
            return 0;
        }
    }
    
    public function rebalance(array $targetAllocation): array
    {
        $current = $this->getPortfolioValue();
        $totalValue = $current['total_value_usd'];
        
        $orders = [];
        
        foreach ($targetAllocation as $ccy => $targetPercent) {
            $targetValue = $totalValue * ($targetPercent / 100);
            
            // Найти текущее значение
            $currentAsset = collect($current['assets'])
                ->firstWhere('currency', $ccy);
            
            $currentValue = $currentAsset['value_usd'] ?? 0;
            $difference = $targetValue - $currentValue;
            
            if (abs($difference) < 10) { // Минимум $10 разница
                continue;
            }
            
            $side = $difference > 0 ? 'buy' : 'sell';
            $amount = abs($difference);
            
            $order = $this->okx->trade()->placeOrder(
                instId: "{$ccy}-USDT",
                tdMode: 'cash',
                side: $side,
                ordType: 'market',
                sz: (string)$amount,
                tgtCcy: 'quote_ccy'
            );
            
            $orders[] = $order;
        }
        
        return $orders;
    }
}

// Использование
$tracker = new PortfolioTracker($client);

// Получить текущий портфель
$portfolio = $tracker->getPortfolioValue();

// Ребалансировка
$orders = $tracker->rebalance([
    'BTC' => 50,  // 50% в BTC
    'ETH' => 30,  // 30% в ETH
    'SOL' => 20   // 20% в SOL
]);
```

## Арбитраж

### Simple Arbitrage Scanner

```php
class ArbitrageScanner
{
    public function __construct(
        private Client $okx,
        private array $exchanges // Другие биржи для сравнения
    ) {}
    
    public function scanOpportunities(array $symbols): array
    {
        $opportunities = [];
        
        foreach ($symbols as $symbol) {
            $okxPrice = $this->getOKXPrice($symbol);
            
            foreach ($this->exchanges as $exchange) {
                $externalPrice = $exchange->getPrice($symbol);
                
                $spread = (($externalPrice - $okxPrice) / $okxPrice) * 100;
                
                if (abs($spread) > 1) { // Более 1% разница
                    $opportunities[] = [
                        'symbol' => $symbol,
                        'okx_price' => $okxPrice,
                        'external_price' => $externalPrice,
                        'spread_percent' => $spread,
                        'direction' => $spread > 0 ? 'buy_okx_sell_external' : 'sell_okx_buy_external'
                    ];
                }
            }
        }
        
        return $opportunities;
    }
    
    private function getOKXPrice(string $symbol): float
    {
        $ticker = $this->okx->market()->getTicker($symbol);
        return (float)$ticker[0]['last'];
    }
}
```

## Бэктестинг

### Simple Backtester

```php
class Backtester
{
    public function __construct(private Client $okx) {}
    
    public function backtest(
        string $symbol,
        string $strategy,
        string $startDate,
        string $endDate
    ): array {
        // Получить исторические данные
        $candles = $this->okx->market()->getHistoryCandles(
            instId: $symbol,
            bar: '1H',
            after: strtotime($startDate) * 1000,
            before: strtotime($endDate) * 1000
        );
        
        $balance = 10000; // Начальный баланс
        $position = 0;
        $trades = [];
        
        foreach ($candles as $candle) {
            [$ts, $open, $high, $low, $close] = $candle;
            
            $signal = $this->$strategy($candles, $candle);
            
            if ($signal === 'buy' && $position == 0) {
                $position = $balance / (float)$close;
                $balance = 0;
                $trades[] = ['type' => 'buy', 'price' => $close, 'time' => $ts];
            } elseif ($signal === 'sell' && $position > 0) {
                $balance = $position * (float)$close;
                $position = 0;
                $trades[] = ['type' => 'sell', 'price' => $close, 'time' => $ts];
            }
        }
        
        // Закрыть позицию если открыта
        if ($position > 0) {
            $lastPrice = (float)$candles[0][4];
            $balance = $position * $lastPrice;
        }
        
        return [
            'initial_balance' => 10000,
            'final_balance' => $balance,
            'profit_percent' => (($balance - 10000) / 10000) * 100,
            'trades' => $trades,
            'total_trades' => count($trades)
        ];
    }
    
    private function simpleMovingAverage($candles, $current): string
    {
        // Простая стратегия на основе SMA
        // Реализация стратегии...
        return 'hold';
    }
}
```

## Reporting

### Daily Trading Report

```php
class TradingReporter
{
    public function __construct(private Client $okx) {}
    
    public function generateDailyReport(): array
    {
        $today = now()->startOfDay();
        
        // Получить сделки за день
        $fills = $this->okx->trade()->getFills(
            begin: $today->timestamp * 1000,
            end: now()->timestamp * 1000
        );
        
        $totalVolume = 0;
        $totalFees = 0;
        $buyCount = 0;
        $sellCount = 0;
        
        foreach ($fills as $fill) {
            $totalVolume += (float)$fill['fillSz'] * (float)$fill['fillPx'];
            $totalFees += (float)$fill['fee'];
            
            if ($fill['side'] === 'buy') {
                $buyCount++;
            } else {
                $sellCount++;
            }
        }
        
        // Получить текущий баланс
        $balance = $this->okx->account()->getBalance();
        $totalEquity = (float)$balance[0]['totalEq'];
        
        return [
            'date' => $today->toDateString(),
            'total_trades' => count($fills),
            'buy_trades' => $buyCount,
            'sell_trades' => $sellCount,
            'total_volume' => $totalVolume,
            'total_fees' => $totalFees,
            'current_equity' => $totalEquity
        ];
    }
}

// Laravel Command
class GenerateDailyReport extends Command
{
    protected $signature = 'okx:daily-report';
    
    public function handle()
    {
        $reporter = new TradingReporter(app(Client::class));
        $report = $reporter->generateDailyReport();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Trades', $report['total_trades']],
                ['Buy Trades', $report['buy_trades']],
                ['Sell Trades', $report['sell_trades']],
                ['Total Volume', '$' . number_format($report['total_volume'], 2)],
                ['Total Fees', '$' . number_format($report['total_fees'], 2)],
                ['Current Equity', '$' . number_format($report['current_equity'], 2)]
            ]
        );
        
        // Отправить по email
        Mail::to('trader@example.com')->send(new DailyReportMail($report));
    }
}
```

---

**Назад:** [← Error Handling](Error-Handling) | **Далее:** [Testing →](Testing)
