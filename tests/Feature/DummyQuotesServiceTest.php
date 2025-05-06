<?php

use Durnerlys\DummyQuotes\Contracts\Cache\CacheServiceInterface;
use Durnerlys\DummyQuotes\Contracts\RateLimiter\RateLimiterInterface;
use Durnerlys\DummyQuotes\Services\Quotes\DummyQuotesService;
use Illuminate\Http\JsonResponse;

beforeEach(function () {
    $this->cacheServiceMock = Mockery::mock(CacheServiceInterface::class);
    $this->rateLimiterMock = Mockery::mock(RateLimiterInterface::class);
    $this->service = new DummyQuotesService($this->cacheServiceMock, $this->rateLimiterMock);
});

it('returns cached data for getAllQuotes', function () {
    $cachedData = ['quotes' => [], 'total' => 0];
    $this->cacheServiceMock
        ->shouldReceive('getCachedDataWithRateLimit')
        ->once()
        ->with('dummy_quotes_all')
        ->andReturn($cachedData);

    $result = $this->service->getAllQuotes();

    expect($result)->toBe($cachedData);
});

it('fetches from API and caches for getAllQuotes', function () {
    $this->cacheServiceMock
        ->shouldReceive('getCachedDataWithRateLimit')
        ->once()
        ->with('dummy_quotes_all')
        ->andReturnNull();

    $apiResponse = Mockery::mock(JsonResponse::class);
    $apiResponse->shouldReceive('successful')->andReturn(true);
    $apiResponse->shouldReceive('json')->andReturn(['quotes' => [], 'total' => 0]);
    $apiResponse->shouldReceive('getData')->andReturn(['quotes' => [], 'total' => 0]);

    $this->rateLimiterMock
        ->shouldReceive('callApiWithRateLimit')
        ->once()
        ->with("{$this->service->baseUrl}/quotes", ['limit' => 0])
        ->andReturn($apiResponse);

    $result = $this->service->getAllQuotes();

    expect($result)->toBe([
        'quotes' => [],
        'total' => 0,
    ]);
});

it('returns cached data for getRandomQuote', function () {
    $cachedData = ['quote' => 'Random quote'];
    $this->cacheServiceMock
        ->shouldReceive('getCachedDataWithRateLimit')
        ->once()
        ->with('dummy_quote_random')
        ->andReturn($cachedData);

    $result = $this->service->getRandomQuote();

    expect($result)->toBe($cachedData);
});

it('fetches from API for getRandomQuote', function () {
    $this->cacheServiceMock
        ->shouldReceive('getCachedDataWithRateLimit')
        ->once()
        ->with('dummy_quote_random')
        ->andReturnNull();

    $apiResponse = Mockery::mock(JsonResponse::class);
    $apiResponse->shouldReceive('successful')->andReturn(true);
    $apiResponse->shouldReceive('json')->andReturn(['quote' => 'Random quote from API']);
    $apiResponse->shouldReceive('getData')->andReturn(['quote' => 'Random quote from API']);

    $this->rateLimiterMock
        ->shouldReceive('callApiWithRateLimit')
        ->once()
        ->with("{$this->service->baseUrl}/quotes/random")
        ->andReturn($apiResponse);

    $result = $this->service->getRandomQuote();

    expect($result)->toBe(['quote' => 'Random quote from API']);
});

it('returns cached data for getQuote', function () {
    $id = 10;
    $cachedData = ['quote' => 'Cached quote by id'];

    $this->cacheServiceMock
        ->shouldReceive('getCachedDataWithRateLimit')
        ->once()
        ->with('dummy_quote_by_id', $id)
        ->andReturn($cachedData);

    $result = $this->service->getQuote($id);

    expect($result)->toBe($cachedData);
});

it('fetches from API for getQuote', function () {
    $id = 10;

    $this->cacheServiceMock
        ->shouldReceive('getCachedDataWithRateLimit')
        ->once()
        ->with('dummy_quote_by_id', $id)
        ->andReturnNull();

    $apiResponse = Mockery::mock(JsonResponse::class);
    $apiResponse->shouldReceive('successful')->andReturn(true);
    $apiResponse->shouldReceive('json')->andReturn(['quote' => 'API quote']);
    $apiResponse->shouldReceive('getData')->andReturn(['quote' => 'API quote']);

    $this->rateLimiterMock
        ->shouldReceive('callApiWithRateLimit')
        ->once()
        ->with("{$this->service->baseUrl}/quotes/{$id}")
        ->andReturn($apiResponse);

    $result = $this->service->getQuote($id);

    expect($result)->toBe(['quote' => 'API quote']);
});
