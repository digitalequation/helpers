<?php

namespace DigitalEquation\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \DigitalEquation\Helpers\Response response()
 * @method static \DigitalEquation\Helpers\Role role()
 *
 * @see \DigitalEquation\Helpers\Helpers
 */
class Helpers extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'helpers';
    }
}
