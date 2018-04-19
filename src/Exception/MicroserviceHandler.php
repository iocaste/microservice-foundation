<?php

namespace Iocaste\Microservice\Exception;

use \Iocaste\Microservice\Foundation\Http\ApiResponse;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Iocaste\Microservice\Foundation\Http\Responds;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MicroserviceHandler extends ExceptionHandler
{
    use Responds;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception $e
     *
     * @throws Exception
     */
    public function report(Exception $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return $this->respondNotFound(
                ApiResponse::CODE_NOT_FOUND,
                $e->getMessage()
            );
        }

        $data = [];

        if (config('app.debug')) {
            $data = [
                'exception' => \get_class($e),
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ];
        }

        if ($this->isHttpException($e)) {
            // Grab the HTTP status code from the Exception
            $status = $e->getStatusCode();
        } else {
            $status = 500;
        }

        return $this->setStatusCode($status)
            ->respondWithError(
                ApiResponse::CODE_API_ERROR,
                'API internal error occurred. Please contact support for details.',
                $data
            );
    }
}
