<?php

namespace Iocaste\Microservice\Foundation\Provider;

use Illuminate\Support\ServiceProvider;

/**
 * Class TranslatorServiceProvider
 */
class TranslatorServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->middleware(\Iocaste\Microservice\Foundation\Http\Middleware\TranslatorMiddleware::class);
    }
}
