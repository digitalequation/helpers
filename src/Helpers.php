<?php

namespace DigitalEquation\Helpers;

class Helpers
{
    /**
     * Instance of the Response class.
     *
     * @return Response
     */
    public static function response(): Response
    {
        return new Response;
    }

    /**
     * Instance of the Role class.
     *
     * @return Role
     */
    public static function role(): Role
    {
        return new Role;
    }
}