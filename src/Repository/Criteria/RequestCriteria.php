<?php

namespace Iocaste\Microservice\Foundation\Repository\Criteria;

use Prettus\Repository\Criteria\RequestCriteria as BaseRequestCriteria;
use Prettus\Repository\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Relations;
use ReflectionMethod;
use Iocaste\Microservice\Foundation\Exception\Repository\Criteria\RequestCriteria\OrderByParameterContainsIllegalSymbols;
use Iocaste\Microservice\Foundation\Exception\Repository\Criteria\RequestCriteria\TryingToJoinUnavailableMethod;

/**
 * Class RequestCriteria
 */
class RequestCriteria extends BaseRequestCriteria
{

    /**
     * @var string
     */
    protected $orderByPattern = '/^[a-z\.\_]+$/i';

    /**
     * @var array
     */
    protected $relationsTypesAllowedForJoin = [
        Relations\BelongsTo::class,
        Relations\HasOne::class,
    ];

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     *
     * @throws OrderByParameterContainsIllegalSymbols
     * @throws TryingToJoinUnavailableMethod
     */
    public function apply($model, RepositoryInterface $repository)
    {
        [$orderBy, $sortedBy] = $this->getOrderParamsAndRemoveThemFromRequest();

        if (!empty($orderBy)) {
            if (!preg_match($this->orderByPattern, $orderBy)) {
                throw new OrderByParameterContainsIllegalSymbols();
            }

            [$relation, $column, $jsonProperty] = $this->getOrderSegments($orderBy);

            if ($relation) {
                $this->joinRelationship($model, $relation);
            }

            $columnWithTableAlias = $relation
                ? str_replace('.', '_', $relation) . '.' . $column
                : $column;

            if ($jsonProperty) {
                $model->orderBy(
                    app('db')->raw('JSON_EXTRACT('
                        . $columnWithTableAlias . ', '
                        . "'$." . $jsonProperty . "'"
                    . ')'),
                    $sortedBy
                );
            } else {
                $model->orderBy($columnWithTableAlias, $sortedBy);
            }
        }

        return parent::apply($model, $repository);
    }

    /**
     * @return array
     */
    protected function getOrderParamsAndRemoveThemFromRequest(): array
    {
        $orderBy = $this->request->get(config('repository.criteria.params.orderBy', 'orderBy'), null);
        $sortedBy = $this->request->get(config('repository.criteria.params.sortedBy', 'sortedBy'), 'asc');

        if ($orderBy) {
            $this->request->offsetSet(config('repository.criteria.params.orderBy', 'orderBy'), null);
        }

        return [
            $orderBy,
            $sortedBy,
        ];
    }

    /**
     * @param $orderBy
     *
     * @return array
     */
    protected function getOrderSegments($orderBy): array
    {
        $orderBy = str_replace(
            'translation_current',
            'translation.' . app('translator')->getLocale(),
            $orderBy
        );

        if (strpos($orderBy, 'translation') !== false) {
            $result = explode('translation', $orderBy);
            $result = array_map(function ($value) {
                return trim($value, '.');
            }, $result);

            return [
                $result[0] ? camel_case($result[0]) : null,
                'translation',
                $result[1],
            ];
        } elseif (strpos($orderBy, '.') !== false) {
            $lastDotPosition = strrpos($orderBy, '.');

            return [
                camel_case(substr($orderBy, 0, $lastDotPosition)),
                substr($orderBy, $lastDotPosition + 1),
                null,
            ];
        }

        return [
            null,
            $orderBy,
            null,
        ];
    }

    /**
     * @param $query
     * @param $relationshipName
     *
     * @throws TryingToJoinUnavailableMethod
     */
    protected function joinRelationship($query, $relationshipName): void
    {
        $usedSegments = [];
        $model = $query->getModel();

        foreach (explode('.', $relationshipName) as $relationshipNameSegment) {
            $relationMethodReflection = new ReflectionMethod(
                get_class($model),
                $relationshipNameSegment
            );

            if (!in_array($relationMethodReflection->getReturnType(), $this->relationsTypesAllowedForJoin)) {
                throw new TryingToJoinUnavailableMethod();
            }

            $relationship = $model->{$relationshipNameSegment}();

            $currentAlias = !empty($usedSegments)
                ? implode('_', $usedSegments)
                : $relationship->getParent()->getTable();

            $alias = !empty($usedSegments)
                ? implode('_', $usedSegments) . '_' . $relationshipNameSegment
                : $relationshipNameSegment;

            if ($relationship instanceof Relations\HasOneOrMany) {
                $joinTableColumn = $relationship->getPlainForeignKey();
                $mainTableColumn = 'id';
            } else {
                $joinTableColumn = $relationship->getOwnerKey();
                $mainTableColumn = $relationship->getForeignKey();
            }

            $query->leftJoin(
                $relationship->getRelated()->getTable() . ' as ' . $alias,
                $alias . '.' . $joinTableColumn,
                '=',
                $currentAlias . '.' . $mainTableColumn
            );

            $model = $relationship->getRelated();
            $usedSegments[] = $relationshipNameSegment;
        }
    }

}
