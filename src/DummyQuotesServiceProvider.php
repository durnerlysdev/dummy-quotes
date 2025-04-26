<?php

namespace Durnerlys\DummyQuotes;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Durnerlys\DummyQuotes\Commands\DummyQuotesCommand;

class DummyQuotesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('dummy-quotes')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_dummy_quotes_table')
            ->hasCommand(DummyQuotesCommand::class);
    }
}
