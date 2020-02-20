<?php

use DigitalEquation\Helpers\{Response, Role};
use Illuminate\Support\Facades\Log;

if (!function_exists('success')) {
    function success($data = [], $statusCode = 200)
    {
        return Response::success($data, $statusCode);
    }
}

if (!function_exists('error')) {
    function error($message = '', $code = 500)
    {
        return Response::error($message, $code);
    }
}

if (!function_exists('role')) {
    function role()
    {
        return new Role;
    }
}

if (!function_exists('l')) {
    function l($var)
    {
        Log::info(print_r($var, true));
    }
}

if (!function_exists('envMix')) {
    function envMix($path)
    {
        $inLocal = config('app.env') === 'local';
        return mix(($inLocal ? '/public/' : '') . $path, $inLocal ? 'dev' : '');
    }
}
