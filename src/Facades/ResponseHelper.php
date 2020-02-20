<?php

namespace DigitalEquation\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory success($data = [], $statusCode = 200)
 * @method static \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory error($message = '', $code = 500)
 *
 * @see \DigitalEquation\Helpers\Response
 */
class ResponseHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'response-helper';
    }
}
