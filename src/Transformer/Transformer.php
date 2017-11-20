<?php

namespace Iocaste\Microservice\Foundation\Transformer;

use League\Fractal\TransformerAbstract;

/**
 * Class Transformer
 */
abstract class Transformer extends TransformerAbstract
{
    /**
     * Checks if specified resource has been requested as query string parameter.
     * @param string $resource
     *
     * @throws \Illuminate\Container\EntryNotFoundException
     * @return bool
     */
    protected function isRequested($resource = null): bool
    {
        $requestKey = config('fractal.auto_includes.request_key');

        if (! config('fractal.auto_includes.enabled')) {
            return false;
        }

        if (! $includes = app('request')->get($requestKey)) {
            return false;
        }

        $includes = explode(',', $includes);

        if (! is_array($includes)) {
            return false;
        }

        if (! in_array($resource, $includes, false)) {
            return false;
        }

        return true;
    }
}
