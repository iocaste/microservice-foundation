<?php

namespace Iocaste\Microservice\Foundation\Data\Models;

/**
 * Class Translatable
 */
trait Translatable
{
    /**
     * Get translation array.
     *
     * @param $value
     * @return array
     */
    public function getTranslationAttribute($value): array
    {
        return json_decode($value, true);
    }

    /**
     * Get name attribute according preset application locale.
     *
     * @return mixed
     */
    public function getTranslatedNameAttribute()
    {
        $locale = app()->translator->getLocale();

        return $this->translation[$locale];
    }
}
