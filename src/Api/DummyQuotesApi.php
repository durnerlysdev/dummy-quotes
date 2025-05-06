<?php

namespace Durnerlys\DummyQuotes\Api;

use Durnerlys\DummyQuotes\Services\Quotes\DummyQuotesService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DummyQuotesApi
{
    public function __construct(protected DummyQuotesService $quoteService)
    {
    }

    /**
     * Returns all quotes as a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllQuotes(): JsonResponse
    {
        $result = $this->quoteService->getAllQuotes();

        if (isset($result['error']) && $result['error'] === true) {

            return response()->json(
                $result,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json([
            'quotes' => $result['quotes'],
            'total' => $result['total'],
        ]);
    }

    /**
     * Returns a random quote as a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRandomQuote(): JsonResponse
    {
        $result = $this->quoteService->getRandomQuote();

        if (isset($result['error']) && $result['error'] === true) {
            return response()->json(
                $result,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json($result);
    }

    /**
     * Returns a specific quote by its ID as a JSON response.
     *
     * @param int $id The ID of the quote to retrieve.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuote(int $id): JsonResponse
    {
        $result = $this->quoteService->getQuote($id);

        if (isset($result['error']) && $result['error'] === true) {
            return response()->json(
                $result,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return response()->json($result);
    }
}
