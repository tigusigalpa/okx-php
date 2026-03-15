<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Tests\Unit;

use Tigusigalpa\OKX\DTO\OKXResponse;
use Tigusigalpa\OKX\Tests\TestCase;

class DTOTest extends TestCase
{
    public function test_okx_response_from_array(): void
    {
        $data = [
            'code' => '0',
            'msg' => 'Success',
            'data' => [
                ['ccy' => 'BTC', 'bal' => '1.5'],
                ['ccy' => 'ETH', 'bal' => '10.0'],
            ],
        ];

        $response = OKXResponse::fromArray($data);

        $this->assertEquals('0', $response->code);
        $this->assertEquals('Success', $response->msg);
        $this->assertCount(2, $response->data);
        $this->assertEquals('BTC', $response->data[0]['ccy']);
    }

    public function test_okx_response_handles_missing_data(): void
    {
        $data = [
            'code' => '0',
            'msg' => 'Success',
        ];

        $response = OKXResponse::fromArray($data);

        $this->assertEquals('0', $response->code);
        $this->assertEquals('Success', $response->msg);
        $this->assertEmpty($response->data);
    }

    public function test_okx_response_handles_empty_array(): void
    {
        $data = [];

        $response = OKXResponse::fromArray($data);

        $this->assertEquals('0', $response->code);
        $this->assertEquals('', $response->msg);
        $this->assertEmpty($response->data);
    }
}
