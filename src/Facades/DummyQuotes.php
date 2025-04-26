<?php

namespace Durnerlys\DummyQuotes\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Durnerlys\DummyQuotes\DummyQuotes
 */
class DummyQuotes extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Durnerlys\DummyQuotes\DummyQuotes::class;
    }
}
