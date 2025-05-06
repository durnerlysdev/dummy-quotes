<?php

namespace Durnerlys\DummyQuotes\Services\RateLimiter;

use Durnerlys\DummyQuotes\Contracts\RateLimiter\RateLimiterInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class RateLimiterService implements RateLimiterInterface
{
    protected int $maxRequests;
    protected int $timeInterval;
    protected string $allQuotesCacheKey = 'dummy_quotes_all';

    public function __construct()
    {
        $this->maxRequests = config('dummy-quotes.max_requests');
        $this->timeInterval = config('dummy-quotes.time_window');
    }

    /**
     * @inheritDoc
     */
    public function generateKey(string $identifier): string
    {
        return 'rate_limit:' . Str::slug($identifier) . ':' . request()->ip();
    }

    /**
     * @inheritDoc
     */
    public function callApiWithRateLimit(string $url, array $queryParams = []): Response|JsonResponse
    {
        $endpointSlug = Str::slug($url);
        $key = $this->generateKey($endpointSlug);

        $result = RateLimiter::attempt(
            $key,
            $this->maxRequests,
            function () use ($url, $queryParams) {
                return Http::get($url, $queryParams);
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
}
