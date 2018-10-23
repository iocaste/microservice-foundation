<?php

namespace Iocaste\Microservice\Foundation\Feature;

/**
 * Trait ServesFeatures
 */
trait ServesFeatures
{
    /**
     * @param $job
     *
     * @return mixed
     */
    public function serve($job)
    {
        return dispatch_now(...\func_get_args());
    }
}
