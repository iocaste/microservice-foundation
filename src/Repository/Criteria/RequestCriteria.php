<?php

namespace Iocaste\Microservice\Foundation\Repository\Criteria;


use Illuminate\Database\Eloquent\Relations;
use Iocaste\Microservice\Foundation\Exception\Repository\Criteria\RequestCriteria\OrderByParameterContainsIllegalSymbols;
use Iocaste\Microservice\Foundation\Exception\Repository\Criteria\RequestCriteria\TryingToJoinUnavailableMethod;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria as BaseRequestCriteria;
use ReflectionMethod;

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
     * @throws OrderByParameterContainsIllegalSymbols
     * @throws TryingToJoinUnavailableMethod
     *
     * @return mixed
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
        $orderByParameterName = config('repository.criteria.params.orderBy', 'orderBy');
        $sortByParameterName = config('repository.criteria.params.sortedBy', 'sortedBy');

        $orderBy = $this->request->get($orderByParameterName, null);
        $sortedBy = $this->request->get($sortByParameterName, 'asc');

        if ($orderBy) {
            $this->request->attributes->remove($orderByParameterName);
            $this->request->query->remove($orderByParameterName);
            $this->request->request->remove($orderByParameterName);
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
