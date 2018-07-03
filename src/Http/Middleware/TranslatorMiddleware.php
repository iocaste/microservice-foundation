<?php

namespace Iocaste\Microservice\Foundation\Http\Middleware;


use Closure;

class TranslatorMiddleware
{
    /**
     * @var array
     */
    protected $defaultLanguages = [
        'ru',
        'en',
    ];

    /**
     * Run the request filter.
     *
     * @param $request
     * @param Closure $next
     * @param bool $validateBelongsTo
     * @param null $resource
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = app('request')->get('lang');

        if (! $lang) {
            $lang = app('request')->segment(1);
        }

        if (! \in_array($lang, $this->defaultLanguages, true)) {
            $lang = env('DEFAULT_LOCALE', 'ru');
        }

        if (app('translator')->getLocale() !== $lang) {
            app('translator')->setLocale($lang);
        }

        return $next($request);
    }
}
