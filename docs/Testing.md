# Testing

Руководство по тестированию приложений, использующих OKX PHP SDK.

## Unit Testing

### Тестирование с Mock

```php
namespace Tests\Unit;

use Tests\TestCase;
use Tigusigalpa\OKX\Client;
use Tigusigalpa\OKX\API\Trade;
use Mockery;

class TradingServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function test_place_order_success()
    {
        // Создать mock клиента
        $mockClient = Mockery::mock(Client::class);
        $mockTrade = Mockery::mock(Trade::class);
        
        $mockClient->shouldReceive('trade')
            ->once()
            ->andReturn($mockTrade);
        
        $mockTrade->shouldReceive('placeOrder')
            ->once()
            ->with(
                'BTC-USDT',
                'cash',
                'buy',
                'market',
                '100'
            )
            ->andReturn([
                [
                    'ordId' => '123456',
                    'clOrdId' => '',
                    'sCode' => '0',
                    'sMsg' => 'success'
                ]
            ]);
        
        $this->app->instance(Client::class, $mockClient);
        
        $service = app(TradingService::class);
        $result = $service->placeMarketOrder('BTC-USDT', 'buy', '100');
        
        $this->assertEquals('123456', $result[0]['ordId']);
    }
    
    public function test_get_balance()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockAccount = Mockery::mock(\Tigusigalpa\OKX\API\Account::class);
        
        $mockClient->shouldReceive('account')
            ->andReturn($mockAccount);
        
        $mockAccount->shouldReceive('getBalance')
            ->with('BTC')
            ->andReturn([
                [
                    'details' => [
                        [
                            'ccy' => 'BTC',
                            'bal' => '1.5',
                            'availBal' => '1.5'
                        ]
                    ]
                ]
            ]);
        
        $this->app->instance(Client::class, $mockClient);
        
        $service = app(TradingService::class);
        $balance = $service->getBalance('BTC');
        
        $this->assertEquals('1.5', $balance);
    }
}
```

### Тестирование с Guzzle Mock

```php
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ClientTest extends TestCase
{
    public function test_successful_api_call()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'code' => '0',
                'msg' => '',
                'data' => [
                    ['ccy' => 'BTC', 'bal' => '1.5']
                ]
            ]))
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        
        $client = new Client(
            apiKey: 'test-key',
            secretKey: 'test-secret',
            passphrase: 'test-pass',
            httpClient: $httpClient
        );
        
        $balance = $client->account()->getBalance('BTC');
        
        $this->assertIsArray($balance);
        $this->assertEquals('BTC', $balance[0]['ccy']);
    }
    
    public function test_api_error_handling()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'code' => '50111',
                'msg' => 'Invalid API key',
                'data' => []
            ]))
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        
        $client = new Client(
            apiKey: 'invalid-key',
            secretKey: 'test-secret',
            passphrase: 'test-pass',
            httpClient: $httpClient
        );
        
        $this->expectException(\Tigusigalpa\OKX\Exceptions\AuthenticationException::class);
        $client->account()->getBalance();
    }
}
```

## Integration Testing

### Настройка тестового окружения

```php
// phpunit.xml
<php>
    <env name="OKX_API_KEY" value="demo-api-key"/>
    <env name="OKX_SECRET_KEY" value="demo-secret-key"/>
    <env name="OKX_PASSPHRASE" value="demo-passphrase"/>
    <env name="OKX_DEMO" value="true"/>
</php>
```

### Интеграционные тесты

```php
namespace Tests\Feature;

use Tests\TestCase;
use Tigusigalpa\OKX\Client;

class OKXIntegrationTest extends TestCase
{
    protected Client $client;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        if (!env('OKX_API_KEY')) {
            $this->markTestSkipped('OKX credentials not configured');
        }
        
        $this->client = new Client(
            apiKey: env('OKX_API_KEY'),
            secretKey: env('OKX_SECRET_KEY'),
            passphrase: env('OKX_PASSPHRASE'),
            isDemo: true
        );
    }
    
    public function test_can_get_server_time()
    {
        $time = $this->client->publicData()->getTime();
        
        $this->assertIsArray($time);
        $this->assertArrayHasKey('ts', $time[0]);
    }
    
    public function test_can_get_instruments()
    {
        $instruments = $this->client->publicData()->getInstruments(
            instType: 'SPOT'
        );
        
        $this->assertIsArray($instruments);
        $this->assertNotEmpty($instruments);
    }
    
    public function test_can_get_ticker()
    {
        $ticker = $this->client->market()->getTicker('BTC-USDT');
        
        $this->assertIsArray($ticker);
        $this->assertEquals('BTC-USDT', $ticker[0]['instId']);
        $this->assertArrayHasKey('last', $ticker[0]);
    }
    
    public function test_can_get_account_balance()
    {
        $balance = $this->client->account()->getBalance();
        
        $this->assertIsArray($balance);
        $this->assertArrayHasKey('details', $balance[0]);
    }
    
    /**
     * @group slow
     */
    public function test_can_place_and_cancel_order()
    {
        // Разместить ордер
        $order = $this->client->trade()->placeOrder(
            instId: 'BTC-USDT',
            tdMode: 'cash',
            side: 'buy',
            ordType: 'limit',
            sz: '0.001',
            px: '10000' // Очень низкая цена, не исполнится
        );
        
        $this->assertIsArray($order);
        $this->assertEquals('0', $order[0]['sCode']);
        
        $orderId = $order[0]['ordId'];
        
        // Отменить ордер
        $cancel = $this->client->trade()->cancelOrder(
            instId: 'BTC-USDT',
            ordId: $orderId
        );
        
        $this->assertEquals('0', $cancel[0]['sCode']);
    }
}
```

## Feature Testing

### Тестирование API endpoints

```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class TradingAPITest extends TestCase
{
    public function test_get_balance_endpoint()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->getJson('/api/balance');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'details' => [
                        '*' => ['ccy', 'bal', 'availBal']
                    ]
                ]
            ]);
    }
    
    public function test_place_order_endpoint()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->postJson('/api/orders', [
                'instId' => 'BTC-USDT',
                'side' => 'buy',
                'amount' => '100'
            ]);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['ordId', 'sCode', 'sMsg']
            ]);
    }
    
    public function test_unauthorized_access()
    {
        $response = $this->getJson('/api/balance');
        
        $response->assertStatus(401);
    }
}
```

## Database Testing

### Тестирование с базой данных

```php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_order_is_stored_in_database()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->postJson('/api/orders', [
                'instId' => 'BTC-USDT',
                'side' => 'buy',
                'amount' => '100'
            ]);
        
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'instrument' => 'BTC-USDT',
            'side' => 'buy'
        ]);
    }
    
    public function test_order_sync_with_okx()
    {
        $order = Order::factory()->create([
            'okx_order_id' => '123456',
            'status' => 'pending'
        ]);
        
        // Mock OKX response
        // ...
        
        $order->syncWithOKX();
        
        $this->assertEquals('filled', $order->fresh()->status);
    }
}
```

## WebSocket Testing

### Тестирование WebSocket подключений

```php
namespace Tests\Unit;

use Tests\TestCase;
use Tigusigalpa\OKX\WebsocketClient;
use Mockery;

class WebSocketTest extends TestCase
{
    public function test_can_subscribe_to_channel()
    {
        $ws = new WebsocketClient(
            apiKey: 'test-key',
            secretKey: 'test-secret',
            passphrase: 'test-pass'
        );
        
        $callbackExecuted = false;
        
        $ws->subscribe('tickers', ['instId' => 'BTC-USDT'], 
            function ($data) use (&$callbackExecuted) {
                $callbackExecuted = true;
            }
        );
        
        // В реальном тесте нужно симулировать получение данных
        // Это упрощенный пример
        
        $this->assertTrue(true);
    }
}
```

## Test Helpers

### Создание тестовых хелперов

```php
namespace Tests;

use Tigusigalpa\OKX\Client;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

trait OKXTestHelpers
{
    protected function createMockClient(array $responses): Client
    {
        $mock = new MockHandler(
            array_map(fn($r) => new Response(200, [], json_encode($r)), $responses)
        );
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new HttpClient(['handler' => $handlerStack]);
        
        return new Client(
            apiKey: 'test-key',
            secretKey: 'test-secret',
            passphrase: 'test-pass',
            httpClient: $httpClient
        );
    }
    
    protected function mockSuccessResponse(array $data = []): array
    {
        return [
            'code' => '0',
            'msg' => '',
            'data' => $data
        ];
    }
    
    protected function mockErrorResponse(string $code, string $msg): array
    {
        return [
            'code' => $code,
            'msg' => $msg,
            'data' => []
        ];
    }
}

// Использование
class MyTest extends TestCase
{
    use OKXTestHelpers;
    
    public function test_something()
    {
        $client = $this->createMockClient([
            $this->mockSuccessResponse([
                ['ccy' => 'BTC', 'bal' => '1.5']
            ])
        ]);
        
        $balance = $client->account()->getBalance();
        
        $this->assertEquals('BTC', $balance[0]['ccy']);
    }
}
```

## Factories

### Order Factory

```php
namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;
    
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'okx_order_id' => $this->faker->numerify('##########'),
            'instrument' => 'BTC-USDT',
            'side' => $this->faker->randomElement(['buy', 'sell']),
            'type' => 'limit',
            'size' => $this->faker->randomFloat(8, 0.001, 1),
            'price' => $this->faker->randomFloat(2, 30000, 70000),
            'status' => 'pending',
        ];
    }
    
    public function filled()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'filled',
                'filled_size' => $attributes['size'],
                'average_price' => $attributes['price'],
            ];
        });
    }
}
```

## Continuous Integration

### GitHub Actions

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: mbstring, xml, ctype, json
          
      - name: Install Dependencies
        run: composer install --prefer-dist --no-progress
        
      - name: Run Unit Tests
        run: vendor/bin/phpunit --testsuite Unit
        
      - name: Run Feature Tests
        run: vendor/bin/phpunit --testsuite Feature
        env:
          OKX_API_KEY: ${{ secrets.OKX_DEMO_API_KEY }}
          OKX_SECRET_KEY: ${{ secrets.OKX_DEMO_SECRET_KEY }}
          OKX_PASSPHRASE: ${{ secrets.OKX_DEMO_PASSPHRASE }}
          OKX_DEMO: true
```

## Best Practices

### 1. Изолируйте тесты

```php
// Плохо
public function test_multiple_things()
{
    $balance = $client->account()->getBalance();
    $this->assertIsArray($balance);
    
    $order = $client->trade()->placeOrder(...);
    $this->assertIsArray($order);
}

// Хорошо
public function test_can_get_balance()
{
    $balance = $client->account()->getBalance();
    $this->assertIsArray($balance);
}

public function test_can_place_order()
{
    $order = $client->trade()->placeOrder(...);
    $this->assertIsArray($order);
}
```

### 2. Используйте моки для внешних API

```php
// Не делайте реальные запросы в unit тестах
public function test_trading_service()
{
    $mockClient = Mockery::mock(Client::class);
    // Настройте mock
    
    $service = new TradingService($mockClient);
    // Тестируйте логику
}
```

### 3. Группируйте медленные тесты

```php
/**
 * @group slow
 * @group integration
 */
public function test_real_api_call()
{
    // Реальный API вызов
}
```

Запуск без медленных тестов:
```bash
vendor/bin/phpunit --exclude-group slow
```

### 4. Используйте data providers

```php
/**
 * @dataProvider orderSideProvider
 */
public function test_place_order_with_different_sides($side)
{
    $order = $this->client->trade()->placeOrder(
        instId: 'BTC-USDT',
        tdMode: 'cash',
        side: $side,
        ordType: 'market',
        sz: '100'
    );
    
    $this->assertEquals($side, $order[0]['side']);
}

public function orderSideProvider()
{
    return [
        ['buy'],
        ['sell']
    ];
}
```

---

**Назад:** [← Examples](Examples) | **Далее:** [API Reference →](API-Reference)
