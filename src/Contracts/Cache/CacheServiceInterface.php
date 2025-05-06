<?php

namespace Durnerlys\DummyQuotes\Contracts\Cache;

use Illuminate\Http\JsonResponse;

interface CacheServiceInterface
{
    /**
     * Retrieve an item from the cache.
     *
     * @param  string  $key  The cache key of the item.
     * @return mixed The value of the item, or null if it doesn't exist.
     */
    public function get(string $key): mixed;

    /**
     * Store an item in the cache.
     *
     * @param  string  $key  The cache key of the item.
     * @param  mixed  $value  The value of the item.
     * @param  int  $ttl  The time-to-live in seconds. Default is 3600 seconds (1 hour).
     */
    public function put(string $key, mixed $value, int $ttl = 3600): void;

    /**
     * Check if an item exists in the cache.
     *
     * @param  string  $key  The cache key of the item.
     * @return bool True if the item exists, false if it doesn't.
     */
    public function has(string $key): bool;

    /**
     * Retrieves data from the cache, handling rate limits and different cache keys.
     *
     * @param  string  $cacheKey  The cache key to use.
     * @param  int|null  $id  The ID, used for binary search.
     * @return mixed|null The cached data if found and within rate limit, otherwise null.
     */
    public function getCachedDataWithRateLimit(string $cacheKey, ?int $id = null): mixed;

    /**
     * Determines if the cached data should be returned based on the rate limit.
     *
     * @param  string  $key  The rate limiter key.
     * @return bool|JsonResponse The API response or a JSON response for rate limit errors.
     */
    public function checkRateLimitForCache(string $key): bool|JsonResponse;

    /**
     * Performs a binary search on the local cache to find a quote by its ID.
     *
     * @param  int  $id  The ID of the quote to search for.
     * @return array|null The quote data if found, null otherwise.
     */
    public function binarySearchLocalCache(string $allQuotesCacheKey, int $id): ?array;
}
