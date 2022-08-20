<?php

namespace Moharami\QueryLogger;

use Illuminate\Support\ServiceProvider;

class QueryLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(QueryLogger $querylogger)
    {
    }


    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'querylogger');

        // Register the main class to use with the facade
        $this->app->singleton('querylogger', function () {
            return new QueryLogger;
        });
    }
}
