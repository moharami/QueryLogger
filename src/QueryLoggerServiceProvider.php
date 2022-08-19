<?php

namespace Moharami\QueryLogger;

use Illuminate\Support\ServiceProvider;
use Psy\Readline\Hoa\Event;

class QueryLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        if (env('APP_DEBUG', true)) {
            $this->startQueryLogger();
        }
    }


    private function startQueryLogger()
    {
        \Event::listen(
            'Illuminate\Database\Events\QueryExecuted',
            function ($data) {
//                $data = compact('bindings', 'time', 'name');
//
//                // Format binding data for sql insertion
//                foreach ($bindings as $i => $binding) {
//                    if (is_object($binding) && $binding instanceof \DateTime) {
//                        $bindings[$i] = '\'' . $binding->format('Y-m-d H:i:s') . '\'';
//                    } elseif (is_null($binding)) {
//                        $bindings[$i] = 'NULL';
//                    } elseif (is_bool($binding)) {
//                        $bindings[$i] = $binding ? '1' : '0';
//                    } elseif (is_string($binding)) {
//                        $bindings[$i] = "'$binding'";
//                    }
//                }
//
//                $query = preg_replace_callback(
//                    '/\?/',
//                    function () use (&$bindings) {
//                        return array_shift($bindings);
//                    }, $query
//                );

                \Log::info($data->sql);
            }
        );
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
