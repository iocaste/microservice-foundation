<?php

namespace Iocaste\Microservice\Foundation\Data\Models\Parameter;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Iocaste\Microservice\Foundation\Data\Models\Translatable;

/**
 * Class Section
 */
class Section extends Model
{
    use Translatable;

    /**
     * @var string
     */
    protected $table = 'parameter_sections';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Disable timestamps for this table.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function parameters(): HasMany
    {
        return $this->hasMany(Parameter::class);
    }
}
