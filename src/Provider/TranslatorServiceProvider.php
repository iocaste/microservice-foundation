<?php

namespace Iocaste\Microservice\Foundation\Provider;

use Illuminate\Support\ServiceProvider;

/**
 * Class TranslatorServiceProvider
 */
class TranslatorServiceProvider extends ServiceProvider
{
    protected $defaultLanguages = [
        'ru',
        'en',
    ];

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
        $lang = app('request')->get('lang');

        if (! $lang) {
            $lang = app('request')->segment(1);
        }

        if (! in_array($lang, $this->defaultLanguages, true)) {
            $lang = env('DEFAULT_LOCALE', 'ru');
        }

        if (app('translator')->getLocale() !== $lang) {
            app('translator')->setLocale($lang);
        }
    }
}
