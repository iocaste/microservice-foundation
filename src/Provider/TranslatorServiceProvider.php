<?php

namespace Iocaste\Microservice\Foundation\Provider;

use Illuminate\Support\ServiceProvider;
use Iocaste\Microservice\Foundation\Http\Middleware\TranslatorMiddleware;

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
        $this->app->middleware(TranslatorMiddleware::class);
    }
}
