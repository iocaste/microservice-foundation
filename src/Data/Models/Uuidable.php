<?php

namespace Iocaste\Microservice\Foundation\Data\Models;

use Webpatser\Uuid\Uuid;

/**
 * Class Uuidable.
 */
trait Uuidable
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function bootUuidable()
    {
        /**
         * Attach to the 'creating' Model Event to provide a UUID
         * for the `id` field (provided by $model->getKeyName()).
         */
        static::creating(function ($model) {
            $key = $model->uuidKeyName ?? 'uuid';
            if (empty($model->$key)) {
                $model->$key = (string) $model->generateNewUuid();
            }
        });

        static::created(function ($model) {
            if ($model->positionable) {
                $model->weight = $model->id * 1000;
                $model->save();
            }
        });
    }

    /**
     * Get a new version 4 ( random ) UUID.
     *
     * @throws \Exception
     * @return string
     */
    public function generateNewUuid(): string
    {
        $uuid = (string) Uuid::generate(4);

        return $this->uuidPrefix . str_replace('-', '', $uuid);
    }
}
