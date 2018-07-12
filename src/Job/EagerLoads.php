<?php

namespace Iocaste\Microservice\Foundation\Job;

/**
 * Interface EagerLoads
 */
interface EagerLoads
{
    /**
     * @return array
     */
    public function getWith(): array;

    /**
     * @return array
     */
    public function getWithCount(): array;
}
