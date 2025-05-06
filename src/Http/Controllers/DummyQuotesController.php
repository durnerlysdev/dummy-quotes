<?php

namespace Durnerlys\DummyQuotes\Http\Controllers;

use Durnerlys\DummyQuotes\Api\DummyQuotesApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class DummyQuotesController extends Controller
{
    protected DummyQuotesApi $api;

    public function __construct(DummyQuotesApi $api)
    {
        $this->api = $api;
    }

    /**
     * Displays the main view for dummy quotes.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        return view('dummy-quotes::index');
    }

    /**
     * Returns all quotes as a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allQuotes(): JsonResponse
    {
        return $this->api->getAllQuotes();
    }

    /**
     * Returns a random quote as a JSON response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function randomQuote(): JsonResponse
    {
        return $this->api->getRandomQuote();
    }

    /**
     * Returns a specific quote by its ID as a JSON response.
     *
     * @param int $id The ID of the quote to retrieve.
     * @return \Illuminate\Http\JsonResponse
     */
    public function quote(int $id): JsonResponse
    {
        return $this->api->getQuote($id);
    }
}
