<?php

namespace Iocaste\Microservice\Foundation\Data\Repositories;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\RepositoryInterface;
use Iocaste\Microservice\Foundation\Data\Models\Parameter\Parameter;

/**
 * Interface ParameterRepository.
 */
interface ParameterContract extends RepositoryInterface
{
    /**
     * @param Parameter $parameter
     * @param Model $model
     *
     * @return mixed
     */
    public function getValue(Parameter $parameter, Model $model);

    /**
     * @param $names
     *
     * @return mixed
     */
    public function findByArrayOfNames($names);

    /**
     * @param Parameter $parameter
     *
     * @return mixed
     */
    public function findChoices(Parameter $parameter);
}
