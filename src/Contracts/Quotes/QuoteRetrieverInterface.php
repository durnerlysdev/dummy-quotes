<?php

namespace Durnerlys\DummyQuotes\Contracts\Quotes;

interface QuoteRetrieverInterface
{
    /**
     * Retrieves all quotes.
     *
     * @return array
     */
    public function getAllQuotes(): array;

    /**
     * Retrieves a random quote.
     *
     * @return array|null
     */
    public function getRandomQuote(): ?array;

    /**
     * Retrieves a quote by its ID.
     *
     * @param int $id The ID of the quote to retrieve.
     * @return array|null
     */
    public function getQuote(int $id): ?array;
}
