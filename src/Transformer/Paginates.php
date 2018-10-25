<?php

namespace Iocaste\Microservice\Foundation\Transformer;

use Illuminate\Http\JsonResponse;

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
                'service' => env('APP_SERVICE_NAME'),
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

    /**
     * @param $builder
     * @param $transformer
     * @param string $key
     *
     * @return JsonResponse
     */
    protected function respondWithCollection($builder, $transformer, string $key = 'data'): JsonResponse
    {
        if (to_boolean($this->request->get('paginate', true)) === false) {
            $collection = fractal()->collection($builder)
                ->transformWith($transformer)
                ->withResourceName($key)
                ->toArray();

            $collection['meta'] = [
                'service' => env('APP_SERVICE_NAME'),
                'entity' => $transformer->getEntity()
            ];
        } else {
            $collection = $this->paginatedCollection(
                $builder,
                $transformer,
                $key,
                $this->request->get('per_page', 20)
            );
        }

        $collection['meta'] = $collection['meta'] ?? [];
        $collection['meta']['service'] = env('APP_SERVICE_NAME');
        $collection['meta']['entity'] = $transformer->getEntity();

        return $this->respondWithSuccess(
            $collection
        );
    }
}
