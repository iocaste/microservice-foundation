<?php

return [

    /**
     * Url path, from where user service can search and retrieve
     * users for authentication.
     */
    'user_service_base_url' => env('USER_SERVICE_BASE_URL', 'http://user_service/v1/users'),

    /**
     * Services that will be preloaded in each microservice instance
     */
    'services' => [

        /**
         * API annotations
         */
        'translator' => [

            /**
             * If enabled, then will be preloaded by default
             */
            'enabled' => true,

            /**
             * Environments, in which service will be preloaded.
             * Use "*" to preload in all environments
             */
            'environments' => ['*'],

            /**
             * Path to service provider
             */
            'provider' => \Iocaste\Microservice\Foundation\Provider\TranslatorServiceProvider::class,

            /**
             * If your app is lumen, config variable will be used to fire "configure" method.
             */
            'config' => null,
        ],

        /**
         * API Error handler.
         */
        'bugsnag' => [

            /**
             * If enabled, then will be preloaded by default
             */
            'enabled' => true,

            /**
             * Environments, in which service will be preloaded.
             * Use "*" to preload in all environments
             */
            'environments' => ['*'],

            /**
             * Path to service provider
             */
            'provider' => \Bugsnag\BugsnagLaravel\BugsnagServiceProvider::class,

            /**
             * If your app is lumen, config variable will be used to fire "configure" method.
             */
            'config' => 'bugsnag',
        ],

        /**
         * API documentation
         */
        'laradox' => [

            /**
             * If enabled, then will be preloaded by default
             */
            'enabled' => true,

            /**
             * Environments, in which service will be preloaded.
             * Use "*" to preload in all environments
             */
            'environments' => ['*'],

            /**
             * Path to service provider
             */
            'provider' => \Iocaste\Laradox\ServiceProvider::class,

            /**
             * If your app is lumen, config variable will be used to fire "configure" method.
             */
            'config' => 'laradox',
        ],

        /**
         * Data transformer for API responses
         */
        'fractal' => [

            /**
             * If enabled, then will be preloaded by default
             */
            'enabled' => true,

            /**
             * Environments, in which service will be preloaded.
             * Use "*" to preload in all environments
             */
            'environments' => ['*'],

            /**
             * Path to service provider
             */
            'provider' => \Spatie\Fractal\FractalServiceProvider::class,

            /**
             * If lumen is used, config variable will be used to fire "configure" method.
             */
            'config' => 'fractal',
        ],

        /**
         * HTTP access control service
         */
        'cors' => [

            /**
             * If enabled, then will be preloaded by default
             */
            'enabled' => false,

            /**
             * Environments, in which service will be preloaded.
             * Use "*" to preload in all environments
             */
            'environments' => ['*'],

            /**
             * Path to service provider
             */
            'provider' => \Barryvdh\Cors\ServiceProvider::class,

            /**
             * If lumen is used, config variable will be used to fire "configure" method.
             */
            'config' => 'cors',
        ],

        /**
         * Form requests package from laravel.
         */
        'form-requests' => [

            /**
             * If enabled, then will be preloaded by default
             */
            'enabled' => true,

            /**
             * Environments, in which service will be preloaded.
             * Use "*" to preload in all environments
             */
            'environments' => ['*'],

            /**
             * Path to service provider
             */
            'provider' => \Iocaste\Form\Providers\FormRequestServiceProvider::class,

            /**
             * If lumen is used, config variable will be used to fire "configure" method.
             */
            'config' => null,
        ],

        /**
         * Service for generating helper files for IDE's
         */
        'ide-helper' => [

            /**
             * If enabled, then will be preloaded by default
             */
            'enabled' => true,

            /**
             * Environments, in which service will be preloaded.
             * Use "*" to preload in all environments
             */
            'environments' => ['testing', 'development', 'local'],

            /**
             * Path to service provider
             */
            'provider' => \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,

            /**
             * If lumen is used, config variable will be used to fire "configure" method.
             */
            'config' => 'ide-helper',
        ],
    ],
];
