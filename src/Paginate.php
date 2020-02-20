<?php

namespace DigitalEquation\Helpers;

use Illuminate\Config\Repository;

class Paginate
{
    /**
     * The driver used for pagination.
     *
     * @var string $driver
     */
    public static string $driver;

    /**
     * Get the pagination driver.
     *
     * @return Repository|mixed|string
     */
    public static function getDriver()
    {
        return self::$driver ?? config('helpers.paginate.driver');
    }

    /**
     * Set the pagination driver.
     *
     * @param string $driver
     */
    public static function setDriver(string $driver): void
    {
        self::$driver = $driver;
    }
}