<?php

declare(strict_types=1);

namespace Tigusigalpa\OKX\API;

class RFQ extends BaseAPI
{
    public function getCounterparties(): array
    {
        return $this->client->request('GET', '/api/v5/rfq/counterparties');
    }

    public function getMakerInstrumentSettings(?string $instType = null): array
    {
        $options = [];
        if ($instType !== null) {
            $options['query'] = ['instType' => $instType];
        }
        return $this->client->request('GET', '/api/v5/rfq/maker-instrument-settings', $options);
    }

    public function getMmpConfig(): array
    {
        return $this->client->request('GET', '/api/v5/rfq/mmp-config');
    }

    public function getMmpState(): array
    {
        return $this->client->request('GET', '/api/v5/rfq/mmp-state');
    }

    public function getPublicTrades(?string $beginId = null, ?string $endId = null, ?int $limit = null): array
    {
        $query = array_filter([
            'beginId' => $beginId,
            'endId' => $endId,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rfq/public-trades', ['query' => $query]);
    }

    public function getQuotes(?string $rfqId = null, ?string $clRfqId = null, ?string $quoteId = null, ?string $clQuoteId = null, ?string $state = null, ?string $beginId = null, ?string $endId = null, ?int $limit = null): array
    {
        $query = array_filter([
            'rfqId' => $rfqId,
            'clRfqId' => $clRfqId,
            'quoteId' => $quoteId,
            'clQuoteId' => $clQuoteId,
            'state' => $state,
            'beginId' => $beginId,
            'endId' => $endId,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rfq/quotes', ['query' => $query]);
    }

    public function getRfqs(?string $rfqId = null, ?string $clRfqId = null, ?string $state = null, ?string $beginId = null, ?string $endId = null, ?int $limit = null): array
    {
        $query = array_filter([
            'rfqId' => $rfqId,
            'clRfqId' => $clRfqId,
            'state' => $state,
            'beginId' => $beginId,
            'endId' => $endId,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rfq/rfqs', ['query' => $query]);
    }

    public function getTrades(?string $rfqId = null, ?string $clRfqId = null, ?string $quoteId = null, ?string $clQuoteId = null, ?string $blockTdId = null, ?string $state = null, ?string $beginId = null, ?string $endId = null, ?string $beginTs = null, ?string $endTs = null, ?int $limit = null): array
    {
        $query = array_filter([
            'rfqId' => $rfqId,
            'clRfqId' => $clRfqId,
            'quoteId' => $quoteId,
            'clQuoteId' => $clQuoteId,
            'blockTdId' => $blockTdId,
            'state' => $state,
            'beginId' => $beginId,
            'endId' => $endId,
            'beginTs' => $beginTs,
            'endTs' => $endTs,
            'limit' => $limit,
        ], fn($v) => $v !== null);

        return $this->client->request('GET', '/api/v5/rfq/trades', ['query' => $query]);
    }

    public function cancelAllAfter(string $timeOut): array
    {
        return $this->client->request('POST', '/api/v5/rfq/cancel-all-after', [
            'json' => ['timeOut' => $timeOut],
        ]);
    }

    public function cancelAllQuotes(): array
    {
        return $this->client->request('POST', '/api/v5/rfq/cancel-all-quotes');
    }

    public function cancelAllRfqs(): array
    {
        return $this->client->request('POST', '/api/v5/rfq/cancel-all-rfqs');
    }

    public function cancelBatchQuotes(array $quoteIds, ?array $clQuoteIds = null): array
    {
        $data = array_filter([
            'quoteIds' => $quoteIds,
            'clQuoteIds' => $clQuoteIds,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/rfq/cancel-batch-quotes', ['json' => $data]);
    }

    public function cancelBatchRfqs(array $rfqIds, ?array $clRfqIds = null): array
    {
        $data = array_filter([
            'rfqIds' => $rfqIds,
            'clRfqIds' => $clRfqIds,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/rfq/cancel-batch-rfqs', ['json' => $data]);
    }

    public function cancelQuote(?string $quoteId = null, ?string $clQuoteId = null, ?string $rfqId = null, ?string $clRfqId = null): array
    {
        $data = array_filter([
            'quoteId' => $quoteId,
            'clQuoteId' => $clQuoteId,
            'rfqId' => $rfqId,
            'clRfqId' => $clRfqId,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/rfq/cancel-quote', ['json' => $data]);
    }

    public function cancelRfq(?string $rfqId = null, ?string $clRfqId = null): array
    {
        $data = array_filter([
            'rfqId' => $rfqId,
            'clRfqId' => $clRfqId,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/rfq/cancel-rfq', ['json' => $data]);
    }

    public function createQuote(string $rfqId, ?string $clQuoteId = null, ?string $quoteSide = null, ?array $legs = null, ?bool $anonymous = null, ?string $expiresIn = null, ?string $tag = null): array
    {
        $data = array_filter([
            'rfqId' => $rfqId,
            'clQuoteId' => $clQuoteId,
            'quoteSide' => $quoteSide,
            'legs' => $legs,
            'anonymous' => $anonymous,
            'expiresIn' => $expiresIn,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/rfq/create-quote', ['json' => $data]);
    }

    public function createRfq(array $counterparties, ?bool $anonymous = null, ?string $clRfqId = null, ?bool $allowPartialExecution = null, ?array $legs = null, ?string $tag = null): array
    {
        $data = array_filter([
            'counterparties' => $counterparties,
            'anonymous' => $anonymous,
            'clRfqId' => $clRfqId,
            'allowPartialExecution' => $allowPartialExecution,
            'legs' => $legs,
            'tag' => $tag,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/rfq/create-rfq', ['json' => $data]);
    }

    public function executeQuote(?string $rfqId = null, ?string $clRfqId = null, ?string $quoteId = null, ?string $clQuoteId = null, ?array $legs = null): array
    {
        $data = array_filter([
            'rfqId' => $rfqId,
            'clRfqId' => $clRfqId,
            'quoteId' => $quoteId,
            'clQuoteId' => $clQuoteId,
            'legs' => $legs,
        ], fn($v) => $v !== null);

        return $this->client->request('POST', '/api/v5/rfq/execute-quote', ['json' => $data]);
    }

    public function setMakerInstrumentSettings(string $instType, array $data): array
    {
        return $this->client->request('POST', '/api/v5/rfq/maker-instrument-settings', [
            'json' => [
                'instType' => $instType,
                'data' => $data,
            ],
        ]);
    }

    public function resetMmp(): array
    {
        return $this->client->request('POST', '/api/v5/rfq/mmp-reset');
    }
}
