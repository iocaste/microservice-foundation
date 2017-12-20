<?php

namespace Iocaste\Microservice\Foundation\Data\Models\Parameter;

use Iocaste\Microservice\Foundation\Data\Models\Translatable;
use Iocaste\Microservice\Foundation\Data\Models\Uuidable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Choice
 */
class Choice extends Model
{
    use Uuidable, Translatable;

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'parameter_choices';

    /**
     * Prefix for unique ID.
     *
     * @var string
     */
    protected $uuidPrefix = 'chc_';
}
