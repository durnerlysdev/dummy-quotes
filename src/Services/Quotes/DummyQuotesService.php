<?php

namespace Durnerlys\DummyQuotes\Services\Quotes;

use Durnerlys\DummyQuotes\Contracts\Cache\CacheServiceInterface;
use Durnerlys\DummyQuotes\Contracts\Quotes\QuoteRetrieverInterface;
use Durnerlys\DummyQuotes\Contracts\RateLimiter\RateLimiterInterface;

class DummyQuotesService implements QuoteRetrieverInterface
{
    public string $baseUrl;

    public int $maxRequests;

    public int $timeInterval;

    public string $allQuotesCacheKey = 'dummy_quotes_all';

    public string $randomQuoteCacheKey = 'dummy_quote_random';

    public string $searchByIdCacheKey = 'dummy_quote_by_id';

    public function __construct(
        protected CacheServiceInterface $cacheService,
        protected RateLimiterInterface $rateLimiter
    ) {
        $this->baseUrl = config('dummy-quotes.base_url');
        $this->maxRequests = config('dummy-quotes.max_requests');
        $this->timeInterval = config('dummy-quotes.time_window');
    }

    /**
     * {@inheritDoc}
     */
    public function getAllQuotes(): array
    {
        // Check the local cache
        $cacheKey = $this->allQuotesCacheKey;

        $cachedQuotesRes = $this->cacheService->getCachedDataWithRateLimit($this->allQuotesCacheKey);

        if (isset($cachedQuotesRes)) {

            return $cachedQuotesRes;
        }

        // Query the API if the local cache does not exist and save it
        $rateLimitApiRes = $this->rateLimiter->callApiWithRateLimit("{$this->baseUrl}/quotes", [
            'limit' => 0,
        ]);

        // When there is an error due to too many requests
        if ($rateLimitApiRes instanceof \Illuminate\Http\JsonResponse) {
            return $rateLimitApiRes->getData(true);
        }

        // Everything is ok
        if ($rateLimitApiRes->successful()) {
            $responseData = $rateLimitApiRes->json();

            if (isset($responseData['quotes'])) {

                $quotesData = [
                    'quotes' => $responseData['quotes'],
                    'total' => $responseData['total'],
                ];

                $this->cacheService->put($cacheKey, $quotesData);

                return $quotesData;
            }
        }

        return [
            'error' => true,
            'message' => 'Failed to fetch quotes.',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getRandomQuote(): ?array
    {
        // Check the local cache
        $cacheKey = $this->randomQuoteCacheKey;

        $cachedQuotesRes = $this->cacheService->getCachedDataWithRateLimit($cacheKey);

        if (isset($cachedQuotesRes)) {

            return $cachedQuotesRes;
        }

        // Query the API if the local cache does not exist and save it
        $response = $this->rateLimiter->callApiWithRateLimit("{$this->baseUrl}/quotes/random");

        // When there is an error due to too many requests
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response->getData(true);
        }

        // Everything is ok
        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => 'Failed to fetch random quote.',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getQuote(int $id): ?array
    {
        // Check the local cache
        $cacheKey = $this->searchByIdCacheKey;

        $cachedQuotesRes = $this->cacheService->getCachedDataWithRateLimit($cacheKey, $id);

        if (isset($cachedQuotesRes)) {
            return $cachedQuotesRes;
        }

        // Query the API if the local cache does not exist and save it
        $response = $this->rateLimiter->callApiWithRateLimit("{$this->baseUrl}/quotes/{$id}");

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            return $response->getData(true);
        }

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => true,
            'message' => 'Failed to search quote.',
        ];
    }
}
