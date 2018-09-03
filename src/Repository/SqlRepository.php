<?php

namespace Iocaste\Microservice\Foundation\Repository;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class MysqlRepository.
 */
abstract class SqlRepository extends Repository
{
    /**
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return parent::all(
            $this->specifyColumnsTable($columns)
        );
    }

    /**
     * @param array $columns
     *
     * @return mixed
     */
    public function first($columns = ['*'])
    {
        return parent::first(
            $this->specifyColumnsTable($columns)
        );
    }

    /**
     * @param string $column
     * @param null $key
     *
     * @return array|\Illuminate\Support\Collection
     */
    public function lists($column, $key = null)
    {
        return parent::lists(
            $this->specifyColumnTable($column),
            $key
        );
    }

    /**
     * @param string $column
     * @param null $key
     *
     * @return array|\Illuminate\Support\Collection
     */
    public function pluck($column, $key = null)
    {
        return parent::pluck(
            $this->specifyColumnTable($column),
            $key
        );
    }

    /**
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return parent::find(
            $id,
            $this->specifyColumnsTable($columns)
        );
    }

    /**
     * @param $field
     * @param null $value
     * @param array $columns
     *
     * @return mixed
     */
    public function findByField($field, $value = null, $columns = ['*'])
    {
        return parent::findByField(
            $field,
            $value,
            $this->specifyColumnsTable($columns)
        );
    }

    /**
     * @param array $where
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*'])
    {
        return parent::findWhere(
            $where,
            $this->specifyColumnsTable($columns)
        );
    }

    /**
     * @param $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        return parent::findWhereIn(
            $field,
            $values,
            $this->specifyColumnsTable($columns)
        );
    }

    /**
     * @param $field
     * @param array $values
     * @param array $columns
     *
     * @return mixed
     */
    public function findWhereNotIn($field, array $values, $columns = ['*'])
    {
        return parent::findWhereNotIn(
            $field,
            $values,
            $this->specifyColumnsTable($columns)
        );
    }

    /**
     * @param null $limit
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        return parent::paginate(
            $limit,
            $this->specifyColumnsTable($columns),
            $method
        );
    }

    /**
     * @param $column
     * @param string $direction
     *
     * @return BaseRepository
     */
    public function orderBy($column, $direction = 'asc')
    {
        return parent::orderBy(
            $this->specifyColumnTable($column),
            $direction
        );
    }

    /**
     * @param $columns
     *
     * @return array
     */
    public function specifyColumnsTable($columns)
    {
        return array_map(function ($column) {
            return $this->specifyColumnTable($column);
        }, $columns);
    }

    /**
     * @param $column
     *
     * @return string
     */
    public function specifyColumnTable($column)
    {
        return !strpos($column, '.')
            ? $this->getTableName() . '.' . $column
            : $column;
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
