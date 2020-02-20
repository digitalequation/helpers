<?php

namespace DigitalEquation\Helpers;

use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response
{
    /**
     * Returns a successful response with optional data
     *
     * @param array $data The array data to be merged
     *
     * @param int   $statusCode
     *
     * @return SymfonyResponse|ResponseFactory
     *
     * @throws Exception when $data is not an array
     */
    public static function success($data = [], $statusCode = 200)
    {
        if (!is_array($data)) {
            throw new Exception('ResponseHelper::success() expects first parameter to be array');
        }

        return response()->json(['success' => true] + $data, $statusCode);
    }

    /**
     * Returns an error response with optional message
     * Note that this function is aware of DB transactions and will roll back in case one is detected
     *
     * @param string $message The error message
     * @param int    $code    The error message
     *
     * @return SymfonyResponse|ResponseFactory
     *
     * @throws Exception when $message is not a string
     */
    public static function error($message = '', $code = 500)
    {

        if (!is_string($message)) {
            throw new Exception('ResponseHelper::error() expects first parameter to be string');
        }

        return response()->json(['success' => false, 'message' => $message], $code);
    }
}