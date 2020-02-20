<?php

namespace DigitalEquation\Helpers\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait OwnershipMethods
{
    /*
     |--------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------
     |
     */

    /**
     * Scope to query only entities belonging to a certain user.
     *
     * @param  Builder $query
     * @param  int     $user_id user ID to query
     * @return Builder
     */
    public function scopeForUser($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    /**
     * Scope to query only entities belonging to currently logged in user.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeForCurrentUser($query)
    {
        return $query->forUser(Auth::user()->id);
    }

    /*
     |--------------------------------------------------------------------------
     | Methods
     |--------------------------------------------------------------------------
     |
     */

    /**
     * Check if current entity is owned by the logged in user.
     *
     * @return boolean
     */
    public function isOwnedByCurrentUser()
    {
        return $this->user_id === Auth::user()->id;
    }

    /**
     * Check if current entity is accessible by the logged in user (owner or admin).
     *
     * @return boolean
     */
    public function isAccessibleByCurrentUser()
    {
        return $this->isOwnedByCurrentUser();
    }
}