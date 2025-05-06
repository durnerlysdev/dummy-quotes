<?php

namespace Durnerlys\DummyQuotes\Contracts\Quotes;

interface QuoteRetrieverInterface
{
    /**
     * Retrieves all quotes.
     */
    public function getAllQuotes(): array;

    /**
     * Retrieves a random quote.
     */
    public function getRandomQuote(): ?array;

    /**
     * Retrieves a quote by its ID.
     *
     * @param  int  $id  The ID of the quote to retrieve.
     */
    public function getQuote(int $id): ?array;
}
