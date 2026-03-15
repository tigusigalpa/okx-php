# Error Handling

Руководство по обработке ошибок в OKX PHP SDK.

## Иерархия исключений

SDK предоставляет специализированные исключения для разных типов ошибок:

```
OKXException (базовое)
├── AuthenticationException
├── RateLimitException
├── InvalidParameterException
└── InsufficientFundsException
```

## Базовое исключение

### OKXException

Базовый класс для всех исключений OKX API.

```php
use Tigusigalpa\OKX\Exceptions\OKXException;

try {
    $balance = $client->account()->getBalance();
} catch (OKXException $e) {
    echo "Код ошибки OKX: " . $e->okxCode;
    echo "Сообщение: " . $e->getMessage();
    echo "Сырой ответ: " . $e->rawResponse;
}
```

**Свойства:**
- `okxCode` - Код ошибки OKX
- `rawResponse` - Полный ответ от API

## Специализированные исключения

### AuthenticationException

Ошибки аутентификации (коды 50111, 50113).

```php
use Tigusigalpa\OKX\Exceptions\AuthenticationException;

try {
    $balance = $client->account()->getBalance();
} catch (AuthenticationException $e) {
    // Неверный API ключ или подпись
    Log::error('OKX Authentication failed', [
        'code' => $e->okxCode,
        'message' => $e->getMessage()
    ]);
    
    // Уведомить администратора
    Mail::to('admin@example.com')->send(new AuthFailedMail($e));
}
```

**Распространенные причины:**
- Неверный API ключ
- Неверный secret key
- Неверный passphrase
- Истекший API ключ
- IP не в whitelist

### RateLimitException

Превышение лимита запросов (код 50011).

```php
use Tigusigalpa\OKX\Exceptions\RateLimitException;

try {
    $order = $client->trade()->placeOrder(...);
} catch (RateLimitException $e) {
    // Подождать и повторить
    sleep(1);
    
    try {
        $order = $client->trade()->placeOrder(...);
    } catch (RateLimitException $e) {
        Log::warning('Rate limit exceeded twice', [
            'endpoint' => 'placeOrder'
        ]);
        throw $e;
    }
}
```

**Стратегии обработки:**

1. **Exponential Backoff**
```php
function retryWithBackoff(callable $callback, int $maxRetries = 3): mixed
{
    $attempt = 0;
    
    while ($attempt < $maxRetries) {
        try {
            return $callback();
        } catch (RateLimitException $e) {
            $attempt++;
            if ($attempt >= $maxRetries) {
                throw $e;
            }
            sleep(pow(2, $attempt)); // 2, 4, 8 секунд
        }
    }
}

$order = retryWithBackoff(fn() => $client->trade()->placeOrder(...));
```

2. **Queue**
```php
// Добавить в очередь вместо немедленного выполнения
PlaceOrderJob::dispatch($instId, $side, $amount)
    ->onQueue('okx-orders');
```

### InvalidParameterException

Неверные параметры запроса (коды 51000-51099).

```php
use Tigusigalpa\OKX\Exceptions\InvalidParameterException;

try {
    $order = $client->trade()->placeOrder(
        instId: 'INVALID-PAIR',
        tdMode: 'cash',
        side: 'buy',
        ordType: 'market',
        sz: '0.001'
    );
} catch (InvalidParameterException $e) {
    // Валидация не прошла
    return response()->json([
        'error' => 'Invalid order parameters',
        'details' => $e->getMessage(),
        'code' => $e->okxCode
    ], 400);
}
```

**Распространенные ошибки:**
- Неверный символ инструмента
- Слишком маленький размер ордера
- Неверный тип ордера
- Отсутствующие обязательные параметры

### InsufficientFundsException

Недостаточно средств (коды 54000-54099).

```php
use Tigusigalpa\OKX\Exceptions\InsufficientFundsException;

try {
    $order = $client->trade()->placeOrder(...);
} catch (InsufficientFundsException $e) {
    // Проверить баланс
    $balance = $client->account()->getBalance();
    
    return response()->json([
        'error' => 'Insufficient funds',
        'available_balance' => $balance[0]['details'][0]['availBal'],
        'required' => $requestedAmount
    ], 400);
}
```

## Комплексная обработка

### Обработка всех типов ошибок

```php
use Tigusigalpa\OKX\Exceptions\{
    OKXException,
    AuthenticationException,
    RateLimitException,
    InvalidParameterException,
    InsufficientFundsException
};

try {
    $order = $client->trade()->placeOrder(
        instId: 'BTC-USDT',
        tdMode: 'cash',
        side: 'buy',
        ordType: 'market',
        sz: '100'
    );
    
    return response()->json(['success' => true, 'order' => $order]);
    
} catch (AuthenticationException $e) {
    Log::critical('OKX Authentication failed', ['error' => $e->getMessage()]);
    return response()->json(['error' => 'Authentication failed'], 401);
    
} catch (RateLimitException $e) {
    Log::warning('OKX Rate limit exceeded');
    return response()->json(['error' => 'Too many requests, please try again later'], 429);
    
} catch (InvalidParameterException $e) {
    Log::info('Invalid order parameters', ['error' => $e->getMessage()]);
    return response()->json(['error' => 'Invalid parameters: ' . $e->getMessage()], 400);
    
} catch (InsufficientFundsException $e) {
    Log::info('Insufficient funds for order');
    return response()->json(['error' => 'Insufficient funds'], 400);
    
} catch (OKXException $e) {
    Log::error('OKX API error', [
        'code' => $e->okxCode,
        'message' => $e->getMessage(),
        'response' => $e->rawResponse
    ]);
    return response()->json(['error' => 'API error: ' . $e->getMessage()], 500);
    
} catch (\Exception $e) {
    Log::error('Unexpected error', ['error' => $e->getMessage()]);
    return response()->json(['error' => 'Internal server error'], 500);
}
```

## Laravel Exception Handler

### Глобальная обработка в Handler

```php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tigusigalpa\OKX\Exceptions\OKXException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->reportable(function (OKXException $e) {
            Log::channel('okx')->error('OKX API Error', [
                'code' => $e->okxCode,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        });
        
        $this->renderable(function (OKXException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'OKX API Error',
                    'code' => $e->okxCode,
                    'message' => $e->getMessage()
                ], 500);
            }
        });
    }
}
```

## Retry механизм

### Автоматический retry

```php
class OKXRetryService
{
    public function executeWithRetry(
        callable $callback,
        int $maxAttempts = 3,
        int $delayMs = 1000
    ): mixed {
        $attempt = 0;
        $lastException = null;
        
        while ($attempt < $maxAttempts) {
            try {
                return $callback();
            } catch (RateLimitException $e) {
                $lastException = $e;
                $attempt++;
                
                if ($attempt < $maxAttempts) {
                    usleep($delayMs * 1000 * $attempt);
                    continue;
                }
            } catch (OKXException $e) {
                // Не повторять для других ошибок
                throw $e;
            }
        }
        
        throw $lastException;
    }
}
```

Использование:
```php
$retryService = new OKXRetryService();

$order = $retryService->executeWithRetry(
    fn() => $client->trade()->placeOrder(...)
);
```

## Валидация перед запросом

### Проверка баланса

```php
class OrderValidator
{
    public function __construct(private Client $okx) {}
    
    public function validateBalance(string $ccy, string $requiredAmount): bool
    {
        try {
            $balance = $this->okx->account()->getBalance($ccy);
            $available = (float) $balance[0]['details'][0]['availBal'];
            
            return $available >= (float) $requiredAmount;
        } catch (OKXException $e) {
            Log::error('Failed to check balance', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    public function validateOrderBeforePlacing(array $orderParams): array
    {
        $errors = [];
        
        // Проверить инструмент
        try {
            $instruments = $this->okx->publicData()->getInstruments(
                instType: 'SPOT',
                instId: $orderParams['instId']
            );
            
            if (empty($instruments)) {
                $errors[] = 'Invalid instrument';
            }
        } catch (OKXException $e) {
            $errors[] = 'Failed to validate instrument';
        }
        
        // Проверить баланс
        if ($orderParams['side'] === 'buy') {
            $quoteCcy = explode('-', $orderParams['instId'])[1];
            if (!$this->validateBalance($quoteCcy, $orderParams['sz'])) {
                $errors[] = 'Insufficient balance';
            }
        }
        
        return $errors;
    }
}
```

## Мониторинг ошибок

### Отправка в Sentry

```php
use Sentry\Laravel\Integration;

try {
    $order = $client->trade()->placeOrder(...);
} catch (OKXException $e) {
    \Sentry\captureException($e);
    
    \Sentry\configureScope(function ($scope) use ($e) {
        $scope->setContext('okx', [
            'code' => $e->okxCode,
            'response' => $e->rawResponse
        ]);
    });
    
    throw $e;
}
```

### Метрики

```php
use Illuminate\Support\Facades\Cache;

class OKXMetrics
{
    public static function recordError(string $type): void
    {
        $key = "okx_errors:{$type}:" . now()->format('Y-m-d-H');
        Cache::increment($key, 1);
        Cache::expire($key, 3600 * 24); // 24 часа
    }
    
    public static function getErrorStats(string $type, int $hours = 24): int
    {
        $total = 0;
        
        for ($i = 0; $i < $hours; $i++) {
            $key = "okx_errors:{$type}:" . now()->subHours($i)->format('Y-m-d-H');
            $total += Cache::get($key, 0);
        }
        
        return $total;
    }
}

// Использование
try {
    $order = $client->trade()->placeOrder(...);
} catch (RateLimitException $e) {
    OKXMetrics::recordError('rate_limit');
    throw $e;
}
```

## Коды ошибок OKX

### Распространенные коды

| Код | Описание | Исключение |
|-----|----------|------------|
| 50111 | Invalid API key | AuthenticationException |
| 50113 | Invalid signature | AuthenticationException |
| 50011 | Rate limit exceeded | RateLimitException |
| 51000-51099 | Invalid parameters | InvalidParameterException |
| 54000-54099 | Insufficient funds | InsufficientFundsException |
| 51008 | Order amount too small | InvalidParameterException |
| 51020 | Order price out of range | InvalidParameterException |

Полный список: [OKX Error Codes](https://www.okx.com/docs-v5/en/#error-code)

## Best Practices

1. **Всегда оборачивайте в try-catch**
```php
try {
    $result = $client->trade()->placeOrder(...);
} catch (OKXException $e) {
    // Обработка
}
```

2. **Логируйте ошибки**
```php
catch (OKXException $e) {
    Log::error('OKX Error', [
        'code' => $e->okxCode,
        'message' => $e->getMessage()
    ]);
}
```

3. **Используйте retry для rate limits**
```php
catch (RateLimitException $e) {
    sleep(1);
    // Повторить
}
```

4. **Валидируйте перед запросом**
```php
if (!$validator->validateBalance(...)) {
    throw new \Exception('Insufficient balance');
}
```

5. **Мониторьте ошибки**
```php
OKXMetrics::recordError('rate_limit');
```

---

**Назад:** [← Laravel Integration](Laravel-Integration) | **Далее:** [Examples →](Examples)
