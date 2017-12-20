<?php

namespace Iocaste\Microservice\Foundation\Data\Models\Parameter;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Iocaste\Microservice\Foundation\Data\Models\Translatable;
use Iocaste\Microservice\Foundation\Data\Models\Uuidable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Parameter
 */
class Parameter extends Model
{
    use Uuidable, Translatable;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Prefix for unique ID.
     *
     * @var string
     */
    protected $uuidPrefix = 'prm_';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'false_visible' => 'boolean',
    ];

    /**
     * @param $value
     * @return mixed
     */
    public function getRulesAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * One-to-Many Relationship
     * One Parameter has Many Choices.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function choices(): HasMany
    {
        return $this->hasMany(\Iocaste\Microservice\Foundation\Data\Models\Parameter\Choice::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(\Iocaste\Microservice\Foundation\Data\Models\Parameter\Section::class);
    }
}
