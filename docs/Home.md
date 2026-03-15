# OKX PHP SDK - Documentation

Добро пожаловать в документацию OKX PHP SDK - production-ready библиотеки для работы с API биржи OKX v5.

## 🚀 Возможности

- **335 REST API эндпоинтов** - Полное покрытие всех категорий API
- **53 WebSocket канала** - Публичные, приватные и бизнес-каналы
- **Laravel 11/12 интеграция** - Service Provider и Facade
- **Framework-agnostic** - Работает standalone или с любым PHP фреймворком
- **Строгая типизация** - Typed DTOs для всех запросов и ответов
- **Demo режим** - Тестирование стратегий без риска
- **PSR-12 стандарты** - Чистый и поддерживаемый код
- **Полное тестирование** - Unit и integration тесты

## 📚 Навигация по документации

### Начало работы
- **[Установка](Installation)** - Установка через Composer и настройка
- **[Быстрый старт](Quick-Start)** - Первые шаги с библиотекой
- **[Laravel интеграция](Laravel-Integration)** - Использование с Laravel

### API документация
- **[REST API](REST-API)** - Работа с REST API (335 эндпоинтов)
- **[WebSocket API](WebSocket-API)** - Работа с WebSocket (53 канала)
- **[API Reference](API-Reference)** - Полный справочник методов

### Руководства
- **[Примеры использования](Examples)** - Продвинутые примеры
- **[Обработка ошибок](Error-Handling)** - Работа с исключениями
- **[Тестирование](Testing)** - Запуск тестов

## 🎯 Быстрый пример

### Laravel
```php
use Tigusigalpa\OKX\Facades\OKX;

// Получить баланс
$balance = OKX::account()->getBalance();

// Разместить ордер
$order = OKX::trade()->placeOrder(
    instId: 'BTC-USDT',
    tdMode: 'cash',
    side: 'buy',
    ordType: 'market',
    sz: '100'
);
```

### Standalone
```php
use Tigusigalpa\OKX\Client;

$client = new Client(
    apiKey: 'your-api-key',
    secretKey: 'your-secret-key',
    passphrase: 'your-passphrase'
);

$ticker = $client->market()->getTicker('BTC-USDT');
```

## 📊 Покрытие API

| Категория | Эндпоинты | Статус |
|-----------|-----------|--------|
| Account | 53 | ✅ |
| Trade | 32 | ✅ |
| TradingBot | 44 | ✅ |
| Finance | 33 | ✅ |
| Asset | 26 | ✅ |
| CopyTrading | 26 | ✅ |
| Market | 24 | ✅ |
| PublicData | 24 | ✅ |
| RFQ | 20 | ✅ |
| Rubik | 15 | ✅ |
| Sprd | 13 | ✅ |
| Fiat | 13 | ✅ |
| Users | 8 | ✅ |
| Support | 2 | ✅ |
| SystemStatus | 1 | ✅ |
| Affiliate | 1 | ✅ |
| **Всего** | **335** | ✅ |

## 🔗 Полезные ссылки

- [GitHub Repository](https://github.com/tigusigalpa/okx-php)
- [Packagist](https://packagist.org/packages/tigusigalpa/okx-php)
- [OKX API Documentation](https://www.okx.com/docs-v5/en/)
- [Issues](https://github.com/tigusigalpa/okx-php/issues)

## 💡 Требования

- PHP 8.2 или выше
- Composer
- Laravel 11.x или 12.x (опционально)

## 📝 Лицензия

MIT License - см. [LICENSE](https://github.com/tigusigalpa/okx-php/blob/main/LICENSE)

## 👤 Автор

**Igor Sazonov**
- Email: sovletig@gmail.com
- GitHub: [@tigusigalpa](https://github.com/tigusigalpa)

---

**Следующий шаг:** [Установка →](Installation)
