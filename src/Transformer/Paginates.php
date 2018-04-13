<?php

namespace Iocaste\Microservice\Foundation\Transformer;

use \Prettus\Repository\Eloquent\BaseRepository;

/**
 * Trait Paginates
 */
trait Paginates
{
    /**
     * @param $builder BaseRepository
     * @param $transformer
     * @param int $perPage
     *
     * @return array
     */
    protected function paginatedCollection(BaseRepository $builder, $transformer, $perPage = 14): array
    {
        $paginator = $builder->paginate($perPage);

        return [
            fractal(
                $paginator->getCollection(),
                $transformer,
                new DataArraySerializer()
            )->withResourceName(false)->toArray(),

            'meta' => [
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
        ][0];
    }
}
