<?php

namespace Iocaste\Microservice\Foundation\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository.
 */
abstract class Repository extends BaseRepository
{
    /**
     * @return $this
     */
    public function withTrashed()
    {
        $this->model = $this->model->withTrashed();

        return $this;
    }

    /**
     * @param string $column
     * @param mixed $model
     *
     * @return string
     */
    public function getColumnName($column, $model = null)
    {
        return $column;
    }

    /**
     * @param array $columns
     * @param mixed $model
     *
     * @return array
     */
    public function getColumnsNames(array $columns, $model = null): array
    {
        return array_map(function ($column) use ($model) {
            return $this->getColumnName($column, $model);
        }, $columns);
    }

    /**
     * @param mixed $model
     *
     * @return string
     */
    public function getTableName($model = null)
    {
        $model = $model ?? $this->model;

        if ($model instanceof Model) {
            return $model->getTable();
        } elseif ($model instanceof EloquentBuilder) {
            return $model->getModel()->getTable();
        }

        return $model->from;
    }
}
