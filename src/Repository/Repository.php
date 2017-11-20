<?php

namespace Iocaste\Microservice\Foundation\Repository;

use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class Repository
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
}
