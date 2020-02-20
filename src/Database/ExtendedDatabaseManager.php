<?php

namespace DigitalEquation\Helpers\Database;

use Closure;
use Exception;

use Illuminate\Database\DatabaseManager;

class ExtendedDatabaseManager extends DatabaseManager
{
    /**
     * Runs the given attempt function inside a transaction wrapper in order to reduce boilerplate
     *
     * @param Closure $attempt The attempt function to wrap around
     * @param mixed $fail Optional fail argument to use in case of exception
     *                         $fail influences failure behavior differently, depending on its type:
     *                         - if Closure         => return $fail()
     *                         - if not set / empty => return result of default fail handler
     *                         - if scalar          => return error($fail)
     *                         - if non-scalar      => return $fail
     *
     * @return mixed
     *
     * @throws Exception
     * @example with Closure $fail      try($logic, function() { return error('My custom message'); });
     * @example with scalar $fail       try($logic, 'My custom message');
     * @example with non-scalar $fail   try($logic, $errorResponse);
     * @example with no $fail           try($logic);
     */
    public function try(Closure $attempt, $fail = null)
    {
        $fail = $fail instanceof Closure ? $fail : function () use ($fail) {
            return empty($fail)
                ? error('Unexpected error occurred...')
                : (is_scalar($fail)
                    ? error($fail)
                    : $fail);
        };

        $this->beginTransaction();

        try {
            $return = $attempt();

            $this->commit();

            return $return;
        } catch (Exception $e) {
            $this->rollBack();

            throw $e;

            return $fail($e);
        }
    }
}
