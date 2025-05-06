<?php

use Illuminate\Support\Facades\Route;
use Durnerlys\DummyQuotes\Http\Controllers\DummyQuotesController;

/**
 * API routes for managing quotes.
 *
 * This group of routes defines the API endpoints for retrieving quotes.
 */
Route::prefix('api/quotes')->group(function () {
    Route::get('/', [DummyQuotesController::class, 'allQuotes'])->name('api.quotes.all');
    Route::get('/random', [DummyQuotesController::class, 'randomQuote'])->name('api.quotes.random');
    Route::get('/{id}', [DummyQuotesController::class, 'quote'])->name('api.quotes.show');
});

/**
 * Catch-all route for the Quotes UI.
 *
 * This route serves the main view for the Quotes user interface.
 * The `{any?}` parameter allows for any subsequent URI segments to be handled by the frontend application.
 *
 * @url GET /quotes-ui/{any?}
 */

Route::get('/quotes-ui/{any?}', [DummyQuotesController::class, 'index']);
