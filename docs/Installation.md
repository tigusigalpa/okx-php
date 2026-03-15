# Установка

Руководство по установке и настройке OKX PHP SDK.

## Требования

- **PHP**: 8.2 или выше
- **Composer**: Последняя версия
- **Laravel**: 11.x или 12.x (опционально)

## Установка через Composer

### 1. Установка пакета

```bash
composer require tigusigalpa/okx-php
```

### 2. Проверка установки

```bash
composer show tigusigalpa/okx-php
```

## Настройка для Laravel

### 1. Публикация конфигурации

```bash
php artisan vendor:publish --tag=okx-config
```

Это создаст файл `config/okx.php`.

### 2. Настройка переменных окружения

Добавьте в файл `.env`:

```env
OKX_API_KEY=your-api-key-here
OKX_SECRET_KEY=your-secret-key-here
OKX_PASSPHRASE=your-passphrase-here
OKX_DEMO=false
OKX_BASE_URL=https://www.okx.com
```

### 3. Получение API ключей

1. Войдите в свой аккаунт на [OKX](https://www.okx.com)
2. Перейдите в **Account** → **API**
3. Создайте новый API ключ
4. Сохраните:
   - API Key
   - Secret Key
   - Passphrase

⚠️ **Важно**: Никогда не публикуйте свои API ключи в публичных репозиториях!

### 4. Настройка прав доступа

При создании API ключа установите необходимые права:

- **Read** - Чтение данных (баланс, ордера)
- **Trade** - Торговля
- **Withdraw** - Вывод средств (опционально)

### 5. IP Whitelist (рекомендуется)

Для дополнительной безопасности добавьте IP адреса серверов в whitelist при создании API ключа.

## Настройка для Standalone использования

Создайте экземпляр клиента напрямую:

```php
use Tigusigalpa\OKX\Client;

$client = new Client(
    apiKey: 'your-api-key',
    secretKey: 'your-secret-key',
    passphrase: 'your-passphrase',
    isDemo: false,  // true для demo режима
    baseUrl: 'https://www.okx.com'
);
```

## Demo режим

### Для Laravel

В `.env`:
```env
OKX_DEMO=true
```

### Для Standalone

```php
$client = new Client(
    apiKey: 'demo-api-key',
    secretKey: 'demo-secret-key',
    passphrase: 'demo-passphrase',
    isDemo: true  // Включить demo режим
);
```

### Получение Demo ключей

1. Перейдите на [OKX Demo Trading](https://www.okx.com/demo-trading)
2. Создайте demo аккаунт
3. Получите demo API ключи

## Конфигурация

### Файл config/okx.php

```php
<?php

return [
    // API ключ
    'api_key' => env('OKX_API_KEY', ''),
    
    // Секретный ключ
    'secret_key' => env('OKX_SECRET_KEY', ''),
    
    // Passphrase
    'passphrase' => env('OKX_PASSPHRASE', ''),
    
    // Demo режим
    'demo' => env('OKX_DEMO', false),
    
    // Базовый URL API
    'base_url' => env('OKX_BASE_URL', 'https://www.okx.com'),
];
```

## Проверка установки

### Laravel

```php
use Tigusigalpa\OKX\Facades\OKX;

Route::get('/test-okx', function () {
    try {
        $time = OKX::publicData()->getTime();
        return response()->json([
            'status' => 'success',
            'server_time' => $time
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
```

### Standalone

```php
try {
    $time = $client->publicData()->getTime();
    echo "Подключение успешно! Время сервера: " . $time[0]['ts'];
} catch (\Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
```

## Опциональные зависимости

### Логирование (PSR-3)

```bash
composer require monolog/monolog
```

Использование:

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

## Устранение проблем

### Ошибка: "Invalid API key"

- Проверьте правильность API ключей
- Убедитесь, что ключи активны
- Проверьте IP whitelist

### Ошибка: "Rate limit exceeded"

- Уменьшите частоту запросов
- Используйте WebSocket для real-time данных

### Ошибка: "Insufficient permissions"

- Проверьте права доступа API ключа
- Убедитесь, что у ключа есть необходимые разрешения

## Обновление

```bash
composer update tigusigalpa/okx-php
```

Проверка версии:

```bash
composer show tigusigalpa/okx-php
```

## Удаление

```bash
composer remove tigusigalpa/okx-php
```

Для Laravel также удалите:
- `config/okx.php`
- Переменные из `.env`

---

**Следующий шаг:** [Быстрый старт →](Quick-Start)
