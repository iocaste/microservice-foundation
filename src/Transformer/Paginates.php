<?php

namespace Iocaste\Microservice\Foundation\Transformer;

/**
 * Trait Paginates
 */
trait Paginates
{
    /**
     * @param $builder
     * @param $transformer
     * @param string $key
     * @param int $perPage
     *
     * @return array
     */
    protected function paginatedCollection($builder, $transformer, string $key = 'data', $perPage = 14): array
    {
        $paginator = $builder->paginate($perPage);

        return [
            $key => fractal(
                $paginator->getCollection(),
                $transformer,
                new DataArraySerializer()
            )->withResourceName(false)->toArray(),

            'meta' => [
                'entity' => $transformer->getEntity(),
                'pagination' => [
                    'total' => $paginator->total(),
                    'count' => $paginator->count(),
                    'per_page' => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'first_page_url' => $paginator->url(1),
                    'last_page_url' => $paginator->url($paginator->lastPage()),
                    'next_page_url' => $paginator->nextPageUrl(),
                    'prev_page_url' => $paginator->previousPageUrl(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                    'has_more' => $paginator->hasMorePages(),
                ],
            ],
        ];
    }
}
