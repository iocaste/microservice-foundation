<?php

namespace Iocaste\Microservice\Foundation;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as LaravelApplication;

/**
 * Class MicroserviceServiceProvider
 */
class MicroserviceServiceProvider extends ServiceProvider
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
        $this->setupConfig();

        $services = config('microservice.services');
        $currentEnv = app()->environment();

        /** @var array $services */
        foreach ($services as $service) {
            if ($service['enabled'] && $this->isInCurrentEnv($currentEnv, $service['environments'])) {
                if (array_get($service, 'config')) {
                    $this->app->configure($service['config']);
                }

                $this->app->register($service['provider']);
            }
        }
    }

    /**
     * @return void
     */
    protected function setupConfig()
    {
        $source = dirname(__DIR__) . '/config/microservice.php';

        if ($this->app instanceof LaravelApplication) {
            $this->publishes([$source => config_path('microservice.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('microservice');
        }

        $this->mergeConfigFrom($source, 'microservice');
    }

    /**
     * @param string $currentEnv
     * @param array $envList
     *
     * @return bool
     */
    protected function isInCurrentEnv(string $currentEnv = '*', array $envList = []): bool
    {
        if (in_array('*', $envList, false)) {
            return true;
        }

        if (in_array($currentEnv, $envList, false)) {
            return true;
        }

        return false;
    }
}
