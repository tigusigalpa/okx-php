<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;
use Tigusigalpa\OKX\API\BaseAPI;
use Tigusigalpa\OKX\Client;
use Tigusigalpa\OKX\DTO\Trade\AttachAlgoOrderRequest;
use Tigusigalpa\OKX\DTO\Trade\PlaceOrderRequest;
use Tigusigalpa\OKX\Tests\TestCase;

/**
 * Verifies the public contract of every API service without making network calls.
 *
 * The API services are intentionally small request builders. Exercising every
 * endpoint here catches malformed method signatures and ensures each method
 * delegates to Client::request().
 */
final class ApiContractTest extends TestCase
{
    /**
     * @return iterable<string, array{class-string<BaseAPI>}>
     */
    public static function apiServices(): iterable
    {
        foreach (glob(dirname(__DIR__, 2) . '/src/API/*.php') as $file) {
            $shortName = pathinfo($file, PATHINFO_FILENAME);

            if ($shortName === 'BaseAPI') {
                continue;
            }

            yield $shortName => ['Tigusigalpa\\OKX\\API\\' . $shortName];
        }
    }

    /** @param class-string<BaseAPI> $serviceClass */
    #[DataProvider('apiServices')]
    public function test_every_endpoint_builds_a_request(string $serviceClass): void
    {
        $client = new RecordingClient();
        $service = new $serviceClass($client);
        $reflection = new ReflectionClass($service);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if ($method->isConstructor() || $method->getDeclaringClass()->getName() !== $serviceClass) {
                continue;
            }

            $requestCount = count($client->requests);
            $result = $method->invokeArgs(
                $service,
                array_map($this->argumentFor(...), $method->getParameters())
            );

            self::assertSame([], $result, "{$serviceClass}::{$method->getName()}() should return the client response.");
            self::assertCount(
                $requestCount + 1,
                $client->requests,
                "{$serviceClass}::{$method->getName()}() should issue exactly one request."
            );

            $request = $client->requests[$requestCount];
            self::assertContains($request['method'], ['GET', 'POST']);
            self::assertStringStartsWith('/api/v5/', $request['path']);
        }
    }

    private function argumentFor(ReflectionParameter $parameter): mixed
    {
        if ($parameter->getName() === 'attachAlgoOrds') {
            return [new AttachAlgoOrderRequest(tpOrdPx: '50000', tpTriggerPx: '50000')];
        }

        $type = $parameter->getType();

        if (!$type instanceof ReflectionNamedType) {
            throw new RuntimeException("Unsupported parameter type for " . $parameter->getName() . ".");
        }

        return match ($type->getName()) {
            'string' => 'test-value',
            'int' => 1,
            'float' => 1.0,
            'bool' => true,
            'array' => ['test-value'],
            PlaceOrderRequest::class => new PlaceOrderRequest('BTC-USDT', 'cash', 'buy', 'market', '1'),
            default => throw new RuntimeException("Unsupported parameter type " . $type->getName() . " for " . $parameter->getName()),
        };
    }
}

final class RecordingClient extends Client
{
    /** @var list<array{method: string, path: string, options: array}> */
    public array $requests = [];

    public function __construct()
    {
    }

    public function request(string $method, string $path, array $options = []): array
    {
        $this->requests[] = [
            'method' => $method,
            'path' => $path,
            'options' => $options,
        ];

        return [];
    }
}
