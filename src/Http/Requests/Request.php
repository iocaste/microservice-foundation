<?php

namespace Iocaste\Microservice\Foundation\Http\Requests;

use Iocaste\Microservice\Foundation\Http\Responds;
use Illuminate\Http\JsonResponse;
use Iocaste\Microservice\Foundation\Http\ApiResponse;
use Iocaste\Form\Http\FormRequest;

/**
 * Class Request.
 */
abstract class Request extends FormRequest
{
    use Responds;

    /**
     * Method throws validation error response.
     *
     * @param  array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $errors): JsonResponse
    {
        return $this->respondValidationError(
            ApiResponse::CODE_VALIDATION_ERROR,
            $this->transformErrors($errors)
        );
    }

    /**
     * Method responds with forbidden if user does not have access to make Request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbiddenResponse(): JsonResponse
    {
        return $this->respondForbidden();
    }

    /**
     * @param $errors
     * @return array
     */
    protected function transformErrors(array $errors = []): array
    {
        $response = [];
        foreach ($errors as $key => $messages) {
            /** @var array $messages */
            foreach ($messages as $message) {
                $response[] = [
                    'field' => $key,
                    'message' => $message,
                ];
            }
        }

        return $response;
    }
}
