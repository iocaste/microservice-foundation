<?php

namespace Iocaste\Microservice\Foundation\Transformer;

use Carbon\Carbon;
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

        if (! \is_array($includes)) {
            return false;
        }

        if (! \in_array($resource, $includes, false)) {
            return false;
        }

        return true;
    }

    /**
     * @param Carbon $dateTime
     *
     * @return array
     */
    protected function getTimeStampAndDate(Carbon $dateTime): array
    {
        return [
            'timestamp' => $dateTime->getTimestamp(),
            'date_time' => $dateTime->format(DATETIME_FULL_FORMAT),
        ];
    }
}
