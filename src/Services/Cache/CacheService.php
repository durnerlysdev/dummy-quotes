<?php

namespace Durnerlys\DummyQuotes\Services\Cache;

use Arr;
use Durnerlys\DummyQuotes\Contracts\Cache\CacheServiceInterface;
use Durnerlys\DummyQuotes\Contracts\RateLimiter\RateLimiterInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Str;

class CacheService implements CacheServiceInterface
{
    protected int $maxRequests;

    protected int $timeInterval;

    protected string $allQuotesCacheKey = 'dummy_quotes_all';

    protected string $randomQuoteCacheKey = 'dummy_quote_random';

    protected string $searchByIdCacheKey = 'dummy_quote_by_id';

    protected string $cacheReadKeyPrefix = 'dummy_quotes_cache_read_';

    protected RateLimiterInterface $rateLimiter;

    public function __construct(
        RateLimiterInterface $rateLimiter
    ) {
        $this->maxRequests = config('dummy-quotes.max_requests');
        $this->timeInterval = config('dummy-quotes.time_window');
        $this->rateLimiter = $rateLimiter;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key): mixed
    {
        return Cache::get($key);
    }

    /**
     * {@inheritDoc}
     */
    public function put(string $key, mixed $value, int $ttl = 3600): void
    {
        Cache::put($key, $value, $ttl);
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $key): bool
    {
        return Cache::has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function checkRateLimitForCache(string $key): bool|JsonResponse
    {
        $endpointSlug = Str::slug($key);
        $key = $this->rateLimiter->generateKey($endpointSlug);

        $result = RateLimiter::attempt(
            $key,
            $this->maxRequests,
            function () {
                return true;
            },
            $this->timeInterval
        );

        if ($result === false) {
            $secondsUntilAvailable = RateLimiter::availableIn($key);

            return response()->json([
                'message' => 'Too many requests.',
                'error' => true,
                'retry_after' => $secondsUntilAvailable,
            ], 429);
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function binarySearchLocalCache(string $allQuotesCacheKey, int $id): ?array
    {
        $cachedArray = $this->get($allQuotesCacheKey);
        $cachedQuotes = $cachedArray['quotes'];

        $start = 0;
        $end = count($cachedQuotes) - 1;

        while ($start <= $end) {
            $middle = floor(($start + $end) / 2);

            if (isset($cachedQuotes[$middle]['id']) && $cachedQuotes[$middle]['id'] === $id) {
                return $cachedQuotes[$middle];
            } elseif (isset($cachedQuotes[$middle]['id']) && $cachedQuotes[$middle]['id'] < $id) {
                $start = $middle + 1;
            } else {
                $end = $middle - 1;
            }
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getCachedDataWithRateLimit(string $cacheKey, ?int $id = null): mixed
    {
        $allQuotesKey = $this->allQuotesCacheKey;
        $rateLimitKey = $this->cacheReadKeyPrefix.Str::slug($cacheKey);

        if ($this->has($allQuotesKey)) {
            $response = $this->checkRateLimitForCache($rateLimitKey);

            if ($response instanceof JsonResponse) {
                return $response->getData(true);
            }

            if ($cacheKey === $this->randomQuoteCacheKey) {
                $randomQuote = $this->get($allQuotesKey);

                return Arr::random($randomQuote['quotes']);
            } elseif ($cacheKey === $this->searchByIdCacheKey && $id !== null) {
                return $this->binarySearchLocalCache($allQuotesKey, $id);
            } else {
                return $this->get($cacheKey);
            }
        }

        return null;
    }
}
