<?php

namespace DigitalEquation\Helpers;

use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * The default roles array.
     *
     * @var array
     */
    private static array $defaults;

    /**
     * Get the default roles list.
     *
     * @return array
     */
    public static function getDefaults(): array
    {
        return self::$defaults ?? config('helpers.default_roles');
    }

    /**
     * Set a list of default user roles with name and description.
     * See the config/helpers.php file for array structure.
     *
     * @param array $defaults
     */
    public static function setDefaults(array $defaults): void
    {
        self::$defaults = $defaults;
    }

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public static function admin(): bool
    {
        return self::is('admin');
    }

    /**
     * Check if the user has the passed role.
     *
     * @param string $role
     * @return bool
     */
    public static function is(string $role): bool
    {
        return Auth::user()->role === $role;
    }

    /**
     * Check if role is moderator.
     *
     * @return bool
     */
    public static function moderator(): bool
    {
        return self::is('moderator');
    }

    /**
     * Check if role is user.
     *
     * @return bool
     */
    public static function user(): bool
    {
        return self::is('user');
    }
}