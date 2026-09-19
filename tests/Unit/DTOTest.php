<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\Tests\Unit;

use Tigusigalpa\OKX\DTO\Account\BalanceResponse;
use Tigusigalpa\OKX\DTO\Market\TickerResponse;
use Tigusigalpa\OKX\DTO\OKXResponse;
use Tigusigalpa\OKX\DTO\Trade\AttachAlgoOrderRequest;
use Tigusigalpa\OKX\DTO\Trade\PlaceOrderRequest;
use Tigusigalpa\OKX\DTO\Trade\PlaceOrderResponse;
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

    public function test_place_order_request_serializes_attached_tpsl_without_null_fields(): void
    {
        $request = new PlaceOrderRequest(
            instId: 'BTC-USDT-SWAP',
            tdMode: 'isolated',
            side: 'sell',
            ordType: 'market',
            sz: '1',
            attachAlgoOrds: [new AttachAlgoOrderRequest(
                tpTriggerPx: '64000',
                tpOrdPx: '-1',
                tpTriggerPxType: 'last',
                slTriggerPx: '66000',
                slOrdPx: '-1',
                slTriggerPxType: 'last',
            )]
        );

        $payload = $request->toArray();

        $this->assertSame('-1', $payload['attachAlgoOrds'][0]['tpOrdPx']);
        $this->assertSame('-1', $payload['attachAlgoOrds'][0]['slOrdPx']);
        $this->assertArrayNotHasKey('sz', $payload['attachAlgoOrds'][0]);
    }

    public function test_balance_response_maps_balance_details(): void
    {
        $response = BalanceResponse::fromArray([
            'uTime' => '1700000000000',
            'totalEq' => '100',
            'details' => [[
                'ccy' => 'BTC',
                'eq' => '1.5',
                'cashBal' => '1.2',
            ]],
        ]);

        self::assertSame('1700000000000', $response->uTime);
        self::assertSame('100', $response->totalEq);
        self::assertCount(1, $response->details);
        self::assertSame('BTC', $response->details[0]->ccy);
        self::assertSame('0', $response->details[0]->availEq);
    }

    public function test_ticker_and_place_order_responses_apply_okx_defaults(): void
    {
        $ticker = TickerResponse::fromArray(['instId' => 'BTC-USDT', 'last' => '50000']);
        $order = PlaceOrderResponse::fromArray(['ordId' => '123', 'sCode' => '0']);

        self::assertSame('BTC-USDT', $ticker->instId);
        self::assertSame('50000', $ticker->last);
        self::assertSame('0', $ticker->bidPx);
        self::assertSame('123', $order->ordId);
        self::assertSame('0', $order->sCode);
        self::assertSame('', $order->sMsg);
    }
}
