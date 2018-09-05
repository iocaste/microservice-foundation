<?php

namespace Iocaste\Microservice\Foundation\Repository;

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
            $this->getColumnsNames($columns)
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
            $this->getColumnsNames($columns)
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
            $this->getColumnName($column),
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
            $this->getColumnName($column),
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
            $this->getColumnsNames($columns)
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
            $this->getColumnName($field),
            $value,
            $this->getColumnsNames($columns)
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
        $fields = array_keys($where);
        $fields = $this->getColumnsNames($fields);

        return parent::findWhere(
            array_combine($fields, array_values($where)),
            $this->getColumnsNames($columns)
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
            $this->getColumnName($field),
            $values,
            $this->getColumnsNames($columns)
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
            $this->getColumnName($field),
            $values,
            $this->getColumnsNames($columns)
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
            $this->getColumnsNames($columns),
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
            $this->getColumnName($column),
            $direction
        );
    }

    /**
     * @param string $column
     * @param mixed $model
     *
     * @return string
     */
    public function getColumnName($column, $model = null): string
    {
        $model = $model ?? $this->model;

        return !strpos($column, '.')
            ? $this->getTableName($model) . '.' . $column
            : $column;
    }
}
