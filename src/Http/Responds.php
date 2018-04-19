<?php

namespace Iocaste\Microservice\Foundation\Http;

use Illuminate\Http\Response as IlluminateResponse;
use App\Http\Controllers\V1\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Class Responds.
 */
trait Responds
{
    /**
     * Default status code.
     *
     * @var int
     */
    protected $statusCode = IlluminateResponse::HTTP_OK;

    /**
     * Status code setter.
     *
     * @param $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Status code getter.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Return JSON encoded response.
     *
     * @param array $data
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond(array $data = [], array $headers = []): JsonResponse
    {
        $meta = [
            'meta' => [
                'server_time' => time(),
                'server_timezone' => date_default_timezone_get(),
                'api_version' => 'v1',
            ],
        ];

        return response()->json(array_merge($data, $meta), $this->getStatusCode(), $headers);
    }

    /**
     * Return response with error.
     *
     * @param null $code
     * @param null $message
     * @param array $data
     *
     * @return JsonResponse
     */
    public function respondWithError($code = null, $message = null, array $data = []): JsonResponse
    {
        return $this->respond([
            'status' => [
                'type' => 'error',
                'message' => $this->getDefaultMessage($code, $message),
                'code' => $code,
                'http_code' => $this->getStatusCode(),
            ],
            'exception' => $data,
        ]);
    }

    /**
     * Returns 400 / Bad request.
     *
     * @param null  $code
     * @param array $data
     * @param null  $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithFailure($code = null, array $data = [], $message = null): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_BAD_REQUEST)->respond([
            'status' => [
                'type' => 'error',
                'message' => $this->getDefaultMessage($code, $message),
                'code' => $code,
                'http_code' => $this->getStatusCode(),
            ],
            'data' => $data,
        ]);
    }

    /**
     * Returns 401 / Unauthorized.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondUnauthorized($message = null): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED)
            ->respondWithError(ApiResponse::CODE_NOT_AUTHORIZED, $message);
    }

    /**
     * Returns 403 / Forbidden.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondForbidden($message = null): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_FORBIDDEN)
            ->respondWithError(ApiResponse::CODE_FORBIDDEN, $message);
    }

    /**
     * Returns 404 / Resource not found response.
     *
     * @param int  $code
     * @param null $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($code = ApiResponse::CODE_NOT_FOUND, $message = null): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)
            ->respondWithError($code, $message);
    }

    /**
     * Returns 422 / Unprocessable entity response.
     *
     * @param int    $code
     * @param array  $errors
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondValidationError(
        $code = ApiResponse::CODE_VALIDATION_ERROR,
        array $errors = [],
        $message = null
    ): JsonResponse {
        return $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->respond([
                'status' => [
                    'type' => 'error',
                    'message' => $this->getDefaultMessage($code, $message),
                    'code' => $code,
                    'http_code' => $this->getStatusCode(),
                ],
                'errors' => $errors,
            ]);
    }

    /**
     * Returns 500 / Internal server error occurred.
     *
     * @param int  $code
     * @param null $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError($code = ApiResponse::CODE_API_ERROR, $message = null): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR)
            ->respondWithError($code, $message);
    }

    /**
     * Returns 200 response with data.
     *
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithSuccess(array $data = []): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_OK)->respond([
            'status' => [
                'http_code' => $this->getStatusCode(),
            ],
            'data' => $data,
        ]);
    }

    /**
     * Returns 201 response with data.
     *
     * @param int   $code
     * @param array $data
     * @param null  $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreated($code = ApiResponse::CODE_SUCCESS, array $data = [], $message = null): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_CREATED)->respond([
            'status' => [
                'type' => 'success',
                'message' => $this->getDefaultMessage($code, $message),
                'code' => $code,
                'http_code' => $this->getStatusCode(),
            ],
            'data' => $data,
        ]);
    }

    /**
     * Returns 204 response, enacted but the response does not include an entity.
     *
     * @param int    $code
     * @param array  $data
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondDeleted($code = ApiResponse::CODE_DELETED, array $data = [], $message = null): JsonResponse
    {
        return $this->setStatusCode(IlluminateResponse::HTTP_NO_CONTENT)->respond([
            'status' => [
                'type' => 'success',
                'message' => $this->getDefaultMessage($code, $message),
                'code' => $code,
                'http_code' => $this->getStatusCode(),
            ],
            'data' => $data,
        ]);
    }

    /**
     * Checks if specified code already has default message.
     *
     * @param $code
     * @param $message
     *
     * @return string
     */
    protected function getDefaultMessage($code, $message): string
    {
        if ($code !== null && $message === null) {
            return ApiResponse::$statusTexts[$code]['message'];
        }

        return $message;
    }
}
