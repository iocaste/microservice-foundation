<?php

namespace Iocaste\Microservice\Foundation\Http;

/**
 * Response codes are build using primary response code + resource entity id + incrementing response message number
 * Example:
 * 200 + 0 + 001
 * 200 + 1 (entity type id) + 001.
 *
 * Entity Types:
 * 0 - No Entity
 * 1 - User
 * 2 - Token
 *
 * Class ApiResponse
 */
abstract class ApiResponse
{
    /**
     * Success without modification
     * Success Codes (200).
     */
    const CODE_SUCCESS = 2000001;

    /**
     * Success with modification
     * Success Codes (201).
     */
    const CODE_CREATED = 2010001;

    /**
     * Success with delete operation
     * Success Codes (204).
     */
    const CODE_DELETED = 2040001;

    /**
     * Client Error Code Constants (400).
     */
    const CODE_CLIENT_BAD_REQUEST = 4000001;

    /**
     * Client Error Code Constants (401).
     */
    const CODE_NOT_AUTHORIZED = 4010001;

    /**
     * Client Error Code Constants (403).
     */
    const CODE_FORBIDDEN = 4030001;

    /**
     * Client Error Code Constants (404).
     */
    const CODE_NOT_FOUND = 4040001;

    /**
     * Validation Error Code Constants (422).
     */
    const CODE_VALIDATION_ERROR = 4220001;

    /**
     * Server Errors Code Constants (500).
     */
    const CODE_API_ERROR = 5000001;
    const CODE_API_UPLOAD_ERROR = 5000002;

    /**
     * Status Code Messages.
     * @var array
     */
    public static $statusTexts = [

        /*
         * 200
         */
        2000001 => [
            'title' => 'SuccessfulRequest',
            'message' => 'Request was performed successfully.',
        ],

        /*
         * 201
         */
        2010001 => [
            'title' => 'SuccessfulOperation',
            'message' => 'Command was performed successfully.',
        ],

        /*
         * 204
         */
        2040001 => [
            'title' => 'SuccessfulResourceRemoval',
            'message' => 'Resource has been successfully deleted.',
        ],

        /*
         * 400
         */
        4000001 => [
            'title' => 'BadRequestException',
            'message' => 'Bad request made from client.',
        ],

        /*
         * 401
         */
        4010001 => [
            'title' => 'UnauthorizedException',
            'message' => 'Invalid or no authentication credentials were provided.',
        ],

        /*
         * 403
         */
        4030001 => [
            'title' => 'ForbiddenException',
            'message' => 'You do not have rights to access this endpoint or perform this action.',
        ],

        /*
         * 404
         */
        4040001 => [
            'title' => 'NotFoundException',
            'message' => 'Resource not found.',
        ],

        /*
         * 422
         */
        4220001 => [
            'title' => 'ValidationException',
            'message' => 'Input validation error.',
        ],

        /*
         * 500
         */
        5000001 => [
            'title' => 'ApiInternalException',
            'message' => 'API internal error occurred. Please contact support for details.',
        ],
    ];
}
