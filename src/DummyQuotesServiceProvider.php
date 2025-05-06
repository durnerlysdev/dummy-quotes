<?php

namespace Durnerlys\DummyQuotes;

use Durnerlys\DummyQuotes\Api\DummyQuotesApi;
use Durnerlys\DummyQuotes\Contracts\Cache\CacheServiceInterface;
use Durnerlys\DummyQuotes\Contracts\Quotes\QuoteRetrieverInterface;
use Durnerlys\DummyQuotes\Contracts\RateLimiter\RateLimiterInterface;
use Durnerlys\DummyQuotes\Services\Cache\CacheService;
use Durnerlys\DummyQuotes\Services\Quotes\DummyQuotesService;
use Durnerlys\DummyQuotes\Services\RateLimiter\RateLimiterService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DummyQuotesServiceProvider extends PackageServiceProvider
{
    /**
     * Configures the package.
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('dummy-quotes')
            ->hasConfigFile()
            ->hasViews('dummy-quotes')
            ->hasRoute('api');
    }

    /**
     * Registers any application services.
     */
    public function packageRegistered(): void
    {
        $this->app->singleton(CacheServiceInterface::class, CacheService::class);
        $this->app->singleton(RateLimiterInterface::class, RateLimiterService::class);
        $this->app->singleton(QuoteRetrieverInterface::class, DummyQuotesService::class);
        $this->app->singleton(DummyQuotesService::class, function ($app) {
            return new DummyQuotesService(
                $app->make(CacheServiceInterface::class),
                $app->make(RateLimiterInterface::class)
            );
        });

        $this->app->singleton(DummyQuotesApi::class, function ($app) {
            return new DummyQuotesApi($app->make(QuoteRetrieverInterface::class));
        });
    }

    /**
     * Boots any package services.
     */
    public function bootingPackage(): void
    {
        $this->publishes([
            __DIR__.'/../config/dummy-quotes.php' => config_path('dummy-quotes.php'),
        ], 'dummy-quotes-config');

        $this->publishes([
            __DIR__.'/../routes/api.php' => base_path('routes/dummy-quotes-api.php'),
        ], 'dummy-quotes-routes');

        $this->publishes([
            __DIR__.'/../dist' => public_path('vendor/durnerlys/dummy-quotes/dist'),
        ], 'dummy-quotes-assets');

        $this->publishes([
            __DIR__.'/../resources/js' => base_path('resources/vendor/durnerlys/dummy-quotes/js'),
        ], 'dummy-quotes-vue-sources');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/durnerlys/dummy-quotes'),
        ], 'dummy-quotes-views');
    }
}
