<?php

namespace Iocaste\Microservice\Foundation\Data\Repositories;

use Illuminate\Database\Eloquent\Model;
use Iocaste\Microservice\Foundation\Data\Models\Parameter\Parameter;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait Parameterizable
 */
trait Parameterizable
{
    /**
     * @param Parameter $parameter
     *
     * @return Collection
     */
    public function findChoices(Parameter $parameter): Collection
    {
        return $parameter->choices()
            ->where('parameter_id', $parameter->id)
            ->get();
    }

    /**
     * @param $names
     * @return Collection
     */
    public function findByArrayOfNames($names): Collection
    {
        return Parameter::whereIn('id', $names)->get();
    }

    /**
     * @param Parameter $parameter
     * @param Model $model
     *
     * @return array|bool|float|int|mixed|null
     */
    public function getValue(Parameter $parameter, $model)
    {
        $modelParameter = $this->getUserParameter($model, $parameter->id);

        if (! $modelParameter) {
            if ($parameter->type === 'boolean') {
                return false;
            }

            return null;
        }

        if ($parameter->type === 'choice') {
            return $this->getParameterSelectedChoice($parameter, $modelParameter);
        }

        if ($parameter->type === 'boolean') {
            return (boolean) $modelParameter->value;
        }

        if ($parameter->type === 'integer') {
            return (int) $modelParameter->value;
        }

        if ($parameter->type === 'decimal') {
            return (float) $modelParameter->value;
        }

        // Return as string by default
        return $modelParameter->value;
    }

    /**
     * @param Parameter $parameter
     * @param null $userParameter
     *
     * @return array|null
     */
    protected function getParameterSelectedChoice(Parameter $parameter, $userParameter = null): ?array
    {
        $userParameterValue = $userParameter->value;

        $value = $parameter->choices()
            ->where('id', $userParameterValue)
            ->where('parameter_id', $parameter->id)
            ->first();

        if (! $value) {
            return null;
        }

        return [
            'id' => $value->id,
            'slug' => $value->slug,
            'name' => $value->translated_name,
        ];
    }

    /**
     * @param User $user
     * @param $parameterId
     * @return Model
     */
    protected function getUserParameter(User $user, $parameterId)
    {
        $filtered = $user->parameters->filter(function ($value, $key) use ($parameterId) {
            return $value->parameter_id === $parameterId;
        });

        return $filtered->first();
    }
}
