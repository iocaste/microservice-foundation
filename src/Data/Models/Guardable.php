<?php

namespace Iocaste\Microservice\Foundation\Data\Models;

/**
 * Trait Guardable
 */
trait Guardable
{
    /**
     * @return int|mixed
     */
    public function getJWTIdentifier()
    {
        return $this->primaryKey;
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
