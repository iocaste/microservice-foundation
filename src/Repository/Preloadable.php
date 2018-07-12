<?php

namespace Iocaste\Microservice\Foundation\Repository;

use League\Fractal\Manager;

/**
 * Class Preloadable
 */
trait Preloadable
{
    /**
     * @return array
     */
    public function getWith(): array
    {
        return $this->getPreloadables()['with'] ?? [];
    }

    /**
     * @return array
     */
    public function getWithCount(): array
    {
        return $this->getPreloadables()['withCount'] ?? [];
    }

    /**
     * @return array
     */
    protected function getPreloadables(): array
    {
        $includes = $this->getIncludes(request()->get(config('fractal.auto_includes.request_key')));
        $preloadableItems = [];

        if (count($includes) === 0) {
            return $this->preloadByDefault;
        }

        foreach ($includes as $include) {
            $items = $this->getPreloadable($include);

            $preloadableItems = array_merge_recursive($items, $preloadableItems);

        }

        return array_merge_recursive($preloadableItems, $this->preloadByDefault);
    }

    /**
     * @param $key
     *
     * @return array|mixed
     */
    protected function getPreloadable($key)
    {
        if (array_key_exists($key, $this->preloadWithIncludes)) {
            return $this->preloadWithIncludes[$key];
        }

        return [];
    }

    /**
     * @param $includes
     *
     * @return array|null
     */
    protected function getIncludes($includes)
    {
        if ($includes === null) {
            return [];
        }

        $fractal = app(Manager::class);

        return $fractal->parseIncludes($includes)->getRequestedIncludes();
    }
}
