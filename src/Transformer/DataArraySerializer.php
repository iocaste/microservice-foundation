<?php

namespace Iocaste\Microservice\Foundation\Transformer;

use League\Fractal\Serializer\ArraySerializer;

/**
 * Class DataArraySerializer
 */
class DataArraySerializer extends ArraySerializer
{
    /**
     * @param string $resourceKey
     * @param array $data
     * @return array
     */
    public function collection($resourceKey, array $data): array
    {
        return $resourceKey === false ? $data : [$resourceKey => $data];
    }

    /**
     * @param string $resourceKey
     * @param array $data
     * @return array
     */
    public function item($resourceKey, array $data): array
    {
        return $resourceKey === false ? $data : [$resourceKey => $data];
    }
}
