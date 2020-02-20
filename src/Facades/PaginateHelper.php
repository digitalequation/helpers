<?php

namespace DigitalEquation\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getDriver()
 * @method static void setDriver(string $driver)
 *
 * @see \DigitalEquation\Helpers\Paginate
 */
class PaginateHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'paginate-helper';
    }
}
