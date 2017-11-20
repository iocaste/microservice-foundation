<?php

namespace Iocaste\Microservice\Foundation\Data\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 */
class User extends Model implements
    AuthenticatableContract,
    JWTSubject
{
    use Guardable, Authenticatable;
}
