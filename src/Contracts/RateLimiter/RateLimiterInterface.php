<?php

namespace Durnerlys\DummyQuotes\Contracts\RateLimiter;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Client\Response;

interface RateLimiterInterface
{
    /**
     * Generates a unique key for rate limiting based on the endpoint and IP address.
     *
     * @param string $identifier A unique identifier for the resource being accessed.
     * @return string The generated rate limit key.
     */
    public function generateKey(string $identifier): string;

    /**
     * Calls the specified API endpoint with rate limiting applied.
     *
     * @param string $url         The URL of the API endpoint.
     * @param array  $queryParams An optional array of query parameters.
     * @return \Response|JsonResponse The API response or a JSON response for rate limit errors.
     */
    public function callApiWithRateLimit(string $url, array $queryParams = []): Response|JsonResponse;
}
