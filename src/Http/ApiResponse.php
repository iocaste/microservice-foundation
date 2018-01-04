<?php

namespace Iocaste\Microservice\Foundation\Http;

/**
 * Response codes are build using primary response code + resource entity id + incrementing response message number
 * Example:
 * 200 + 00 + 001
 * 200 + 01 (entity type id) + 001.
 *
 * Entity Types:
 * 00 - No Entity
 * 01 - User
 * 02 - Token
 *
 * Class ApiResponse
 */
abstract class ApiResponse
{
    /**
     * Success without modification
     * Success Codes (200).
     */
    public const CODE_SUCCESS = 20000001;

    /**
     * Success with modification
     * Success Codes (201).
     */
    public const CODE_CREATED = 20100001;

    /**
     * Success with delete operation
     * Success Codes (204).
     */
    public const CODE_DELETED = 20400001;

    /**
     * Client Error Code Constants (400).
     */
    public const CODE_CLIENT_BAD_REQUEST = 40000001;

    /**
     * Client Error Code Constants (401).
     */
    public const CODE_NOT_AUTHORIZED = 40100001;

    /**
     * Client Error Code Constants (403).
     */
    public const CODE_FORBIDDEN = 40300001;

    /**
     * Client Error Code Constants (404).
     */
    public const CODE_NOT_FOUND = 40400001;

    /**
     * Validation Error Code Constants (422).
     */
    public const CODE_VALIDATION_ERROR = 42200001;

    /**
     * Server Errors Code Constants (500).
     */
    public const CODE_API_ERROR = 50000001;
    public const CODE_API_UPLOAD_ERROR = 50000002;

    /**
     * Status Code Messages.
     * @var array
     */
    public static $statusTexts = [

        /*
         * 200
         */
        20000001 => [
            'title' => 'SuccessfulRequest',
            'message' => 'Request was performed successfully.',
        ],

        /*
         * 201
         */
        20100001 => [
            'title' => 'SuccessfulOperation',
            'message' => 'Command was performed successfully.',
        ],

        /*
         * 204
         */
        20400001 => [
            'title' => 'SuccessfulResourceRemoval',
            'message' => 'Resource has been successfully deleted.',
        ],

        /*
         * 400
         */
        40000001 => [
            'title' => 'BadRequestException',
            'message' => 'Bad request made from client.',
        ],

        /*
         * 401
         */
        40100001 => [
            'title' => 'UnauthorizedException',
            'message' => 'Invalid or no authentication credentials were provided.',
        ],

        /*
         * 403
         */
        40300001 => [
            'title' => 'ForbiddenException',
            'message' => 'You do not have rights to access this endpoint or perform this action.',
        ],

        /*
         * 404
         */
        40400001 => [
            'title' => 'NotFoundException',
            'message' => 'Resource not found.',
        ],

        /*
         * 422
         */
        42200001 => [
            'title' => 'ValidationException',
            'message' => 'Input validation error.',
        ],

        /*
         * 500
         */
        50000001 => [
            'title' => 'ApiInternalException',
            'message' => 'API internal error occurred. Please contact support for details.',
        ],
    ];
}
