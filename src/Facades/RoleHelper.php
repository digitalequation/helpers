<?php

namespace DigitalEquation\Helpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getDefaults()
 * @method static void setDefaults(array $defaults)
 * @method static bool admin()
 * @method static bool moderator()
 * @method static bool user()
 * @method static bool is(string $role)
 *
 * @see \DigitalEquation\Helpers\Role
 */
class RoleHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'role-helper';
    }
}
