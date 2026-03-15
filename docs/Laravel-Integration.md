# Laravel Integration

Полное руководство по интеграции OKX PHP SDK с Laravel 11/12.

## Установка

### 1. Установка пакета

```bash
composer require tigusigalpa/okx-php
```

### 2. Публикация конфигурации

```bash
php artisan vendor:publish --tag=okx-config
```

Это создаст файл `config/okx.php`.

### 3. Настройка .env

```env
OKX_API_KEY=your-api-key
OKX_SECRET_KEY=your-secret-key
OKX_PASSPHRASE=your-passphrase
OKX_DEMO=false
```

## Использование Facade

### Базовое использование

```php
use Tigusigalpa\OKX\Facades\OKX;

class TradingController extends Controller
{
    public function getBalance()
    {
        $balance = OKX::account()->getBalance();
        
        return response()->json($balance);
    }
    
    public function placeOrder(Request $request)
    {
        $order = OKX::trade()->placeOrder(
            instId: $request->instId,
            tdMode: 'cash',
            side: $request->side,
            ordType: 'market',
            sz: $request->amount
        );
        
        return response()->json($order);
    }
}
```

### Dependency Injection

```php
use Tigusigalpa\OKX\Client;

class TradingService
{
    public function __construct(
        private Client $okx
    ) {}
    
    public function getCurrentPrice(string $symbol): float
    {
        $ticker = $this->okx->market()->getTicker($symbol);
        return (float) $ticker[0]['last'];
    }
    
    public function buyMarket(string $symbol, string $amount): array
    {
        return $this->okx->trade()->placeOrder(
            instId: $symbol,
            tdMode: 'cash',
            side: 'buy',
            ordType: 'market',
            sz: $amount,
            tgtCcy: 'quote_ccy'
        );
    }
}
```

## Artisan команды

### Создание команды для мониторинга

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Tigusigalpa\OKX\Facades\OKX;

class MonitorPrice extends Command
{
    protected $signature = 'okx:monitor {symbol}';
    protected $description = 'Monitor OKX price for a symbol';

    public function handle()
    {
        $symbol = $this->argument('symbol');
        
        $this->info("Monitoring {$symbol}...");
        
        while (true) {
            $ticker = OKX::market()->getTicker($symbol);
            $price = $ticker[0]['last'];
            
            $this->line(now()->format('H:i:s') . " - {$symbol}: \${price}");
            
            sleep(5);
        }
    }
}
```

Запуск:
```bash
php artisan okx:monitor BTC-USDT
```

### Команда для получения баланса

```php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Tigusigalpa\OKX\Facades\OKX;

class GetBalance extends Command
{
    protected $signature = 'okx:balance {currency?}';
    protected $description = 'Get OKX account balance';

    public function handle()
    {
        $currency = $this->argument('currency');
        
        $balance = OKX::account()->getBalance($currency);
        
        $this->table(
            ['Currency', 'Balance', 'Available', 'Frozen'],
            collect($balance[0]['details'] ?? [])->map(fn($detail) => [
                $detail['ccy'],
                $detail['bal'],
                $detail['availBal'],
                $detail['frozenBal']
            ])
        );
    }
}
```

## Jobs и Queues

### Job для размещения ордера

```php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tigusigalpa\OKX\Facades\OKX;

class PlaceOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $instId,
        public string $side,
        public string $amount,
    ) {}

    public function handle(): void
    {
        $order = OKX::trade()->placeOrder(
            instId: $this->instId,
            tdMode: 'cash',
            side: $this->side,
            ordType: 'market',
            sz: $this->amount,
            tgtCcy: 'quote_ccy'
        );
        
        \Log::info('Order placed', ['order' => $order]);
    }
}
```

Использование:
```php
PlaceOrderJob::dispatch('BTC-USDT', 'buy', '100');
```

### Job для мониторинга цены

```php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Tigusigalpa\OKX\Facades\OKX;
use App\Notifications\PriceAlert;

class MonitorPriceJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        public string $symbol,
        public float $targetPrice,
        public string $direction, // 'above' or 'below'
    ) {}

    public function handle(): void
    {
        $ticker = OKX::market()->getTicker($this->symbol);
        $currentPrice = (float) $ticker[0]['last'];
        
        $triggered = match($this->direction) {
            'above' => $currentPrice >= $this->targetPrice,
            'below' => $currentPrice <= $this->targetPrice,
        };
        
        if ($triggered) {
            auth()->user()->notify(
                new PriceAlert($this->symbol, $currentPrice, $this->targetPrice)
            );
        } else {
            // Повторить через 1 минуту
            self::dispatch($this->symbol, $this->targetPrice, $this->direction)
                ->delay(now()->addMinute());
        }
    }
}
```

## Events и Listeners

### Event для нового ордера

```php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class OrderPlaced
{
    use Dispatchable;

    public function __construct(
        public array $order
    ) {}
}
```

### Listener для логирования

```php
namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

class LogOrderPlaced
{
    public function handle(OrderPlaced $event): void
    {
        Log::info('Order placed via OKX', [
            'order_id' => $event->order['ordId'],
            'instrument' => $event->order['instId'],
            'side' => $event->order['side'],
        ]);
    }
}
```

## Middleware

### Rate Limiting Middleware

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OKXRateLimit
{
    public function handle(Request $request, Closure $next)
    {
        $key = 'okx_rate_limit:' . $request->user()->id;
        
        if (Cache::has($key)) {
            return response()->json([
                'error' => 'Too many requests to OKX API'
            ], 429);
        }
        
        Cache::put($key, true, now()->addSeconds(1));
        
        return $next($request);
    }
}
```

## Service Provider

Создание собственного Service Provider:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tigusigalpa\OKX\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class OKXServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('okx.logger', function () {
            $logger = new Logger('okx');
            $logger->pushHandler(
                new StreamHandler(storage_path('logs/okx.log'), Logger::DEBUG)
            );
            return $logger;
        });
        
        $this->app->extend(Client::class, function ($client, $app) {
            return new Client(
                apiKey: config('okx.api_key'),
                secretKey: config('okx.secret_key'),
                passphrase: config('okx.passphrase'),
                isDemo: config('okx.demo'),
                logger: $app->make('okx.logger')
            );
        });
    }
}
```

## Модели и Eloquent

### Модель для хранения ордеров

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'okx_order_id',
        'instrument',
        'side',
        'type',
        'size',
        'price',
        'status',
        'filled_size',
        'average_price',
    ];
    
    protected $casts = [
        'size' => 'decimal:8',
        'price' => 'decimal:2',
        'filled_size' => 'decimal:8',
        'average_price' => 'decimal:2',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function syncWithOKX()
    {
        $okxOrder = \OKX::trade()->getOrder(
            instId: $this->instrument,
            ordId: $this->okx_order_id
        );
        
        $this->update([
            'status' => $okxOrder[0]['state'],
            'filled_size' => $okxOrder[0]['accFillSz'],
            'average_price' => $okxOrder[0]['avgPx'],
        ]);
    }
}
```

### Repository Pattern

```php
namespace App\Repositories;

use Tigusigalpa\OKX\Client;
use App\Models\Order;

class TradingRepository
{
    public function __construct(
        private Client $okx
    ) {}
    
    public function placeAndStore(
        int $userId,
        string $instId,
        string $side,
        string $size,
        ?string $price = null
    ): Order {
        $ordType = $price ? 'limit' : 'market';
        
        $okxOrder = $this->okx->trade()->placeOrder(
            instId: $instId,
            tdMode: 'cash',
            side: $side,
            ordType: $ordType,
            sz: $size,
            px: $price
        );
        
        return Order::create([
            'user_id' => $userId,
            'okx_order_id' => $okxOrder[0]['ordId'],
            'instrument' => $instId,
            'side' => $side,
            'type' => $ordType,
            'size' => $size,
            'price' => $price,
            'status' => 'pending',
        ]);
    }
    
    public function getActiveOrders(int $userId)
    {
        return Order::where('user_id', $userId)
            ->whereIn('status', ['pending', 'partially_filled'])
            ->get();
    }
}
```

## API Routes

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TradingController;

Route::middleware(['auth:sanctum'])->group(function () {
    // Баланс
    Route::get('/balance', [TradingController::class, 'getBalance']);
    
    // Рыночные данные
    Route::get('/ticker/{symbol}', [TradingController::class, 'getTicker']);
    Route::get('/orderbook/{symbol}', [TradingController::class, 'getOrderBook']);
    
    // Торговля
    Route::post('/orders', [TradingController::class, 'placeOrder']);
    Route::get('/orders', [TradingController::class, 'getOrders']);
    Route::delete('/orders/{orderId}', [TradingController::class, 'cancelOrder']);
    
    // Позиции
    Route::get('/positions', [TradingController::class, 'getPositions']);
});
```

## WebSocket в Laravel

### Использование с Laravel Echo

```php
namespace App\Services;

use Tigusigalpa\OKX\WebsocketClient;
use Illuminate\Support\Facades\Broadcast;

class OKXWebSocketService
{
    private WebsocketClient $ws;
    
    public function __construct()
    {
        $this->ws = new WebsocketClient(
            apiKey: config('okx.api_key'),
            secretKey: config('okx.secret_key'),
            passphrase: config('okx.passphrase')
        );
    }
    
    public function startPriceStream(string $symbol): void
    {
        $this->ws->connectPublic();
        
        $this->ws->subscribe('tickers', ['instId' => $symbol], function ($data) use ($symbol) {
            $ticker = $data['data'][0];
            
            // Broadcast через Laravel Echo
            Broadcast::channel('prices.' . $symbol)->send([
                'price' => $ticker['last'],
                'volume' => $ticker['vol24h'],
                'change' => $ticker['sodUtc0'],
            ]);
        });
        
        $this->ws->run();
    }
}
```

## Тестирование

### Feature Test

```php
namespace Tests\Feature;

use Tests\TestCase;
use Tigusigalpa\OKX\Facades\OKX;
use Illuminate\Support\Facades\Http;

class TradingTest extends TestCase
{
    public function test_can_get_balance()
    {
        $response = $this->getJson('/api/balance');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['ccy', 'bal', 'availBal']
            ]);
    }
    
    public function test_can_place_order()
    {
        $response = $this->postJson('/api/orders', [
            'instId' => 'BTC-USDT',
            'side' => 'buy',
            'amount' => '100'
        ]);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['ordId', 'clOrdId', 'sCode']
            ]);
    }
}
```

### Unit Test с Mock

```php
namespace Tests\Unit;

use Tests\TestCase;
use Tigusigalpa\OKX\Client;
use Mockery;

class TradingServiceTest extends TestCase
{
    public function test_get_current_price()
    {
        $mock = Mockery::mock(Client::class);
        $mock->shouldReceive('market->getTicker')
            ->with('BTC-USDT')
            ->andReturn([['last' => '50000']]);
        
        $this->app->instance(Client::class, $mock);
        
        $service = app(TradingService::class);
        $price = $service->getCurrentPrice('BTC-USDT');
        
        $this->assertEquals(50000, $price);
    }
}
```

## Конфигурация для разных окружений

### config/okx.php

```php
return [
    'api_key' => env('OKX_API_KEY'),
    'secret_key' => env('OKX_SECRET_KEY'),
    'passphrase' => env('OKX_PASSPHRASE'),
    'demo' => env('OKX_DEMO', env('APP_ENV') !== 'production'),
    'base_url' => env('OKX_BASE_URL', 'https://www.okx.com'),
    
    // Дополнительные настройки
    'timeout' => env('OKX_TIMEOUT', 30),
    'retry_times' => env('OKX_RETRY_TIMES', 3),
];
```

### .env.production

```env
OKX_API_KEY=production-key
OKX_SECRET_KEY=production-secret
OKX_PASSPHRASE=production-passphrase
OKX_DEMO=false
```

### .env.testing

```env
OKX_API_KEY=demo-key
OKX_SECRET_KEY=demo-secret
OKX_PASSPHRASE=demo-passphrase
OKX_DEMO=true
```

## Логирование

### Настройка канала логирования

```php
// config/logging.php
'channels' => [
    'okx' => [
        'driver' => 'daily',
        'path' => storage_path('logs/okx.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
],
```

### Использование

```php
use Illuminate\Support\Facades\Log;

Log::channel('okx')->info('Order placed', [
    'order_id' => $order['ordId'],
    'instrument' => $order['instId'],
]);
```

---

**Назад:** [← WebSocket API](WebSocket-API) | **Далее:** [Error Handling →](Error-Handling)
