<?php

use Illuminate\Contracts\Debug\ExceptionHandler;
use Symfony\Component\Debug\Exception\FatalThrowableError;

if (! function_exists('bugsnag')) {
    /**
     * @return \Bugsnag\Client|\Laravel\Lumen\Application|mixed|bugsnag
     */
    function bugsnag()
    {
        return app('bugsnag');
    }
}

if (! function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param  string  $path
     * @return string
     */
    function app_path($path = '')
    {
        return app('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('request')) {
    /**
     * Get current request
     * required by freshbitsweb/laravel-log-enhancer package
     *
     * @return Illuminate\Support\Facades\Request
     */
    function request()
    {
        return app('request');
    }
}

if (! function_exists('to_boolean')) {
    /**
     * @param $value
     *
     * @return boolean
     */
    function to_boolean($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}

if (! function_exists('report')) {
    /**
     * Report an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    function report($exception)
    {
        if ($exception instanceof Throwable &&
            ! $exception instanceof Exception) {
            $exception = new FatalThrowableError($exception);
        }

        app(ExceptionHandler::class)->report($exception);
    }
}
