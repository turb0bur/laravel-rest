<?php

namespace App\Traits;

use App\User;

trait AdminAction
{
    /**
     * Check before an action whether the authenticated user is an admin.
     *
     * @param \App\User $user
     * @param           $ability
     * @return bool
     */
    public function before(User $user, $ability): bool
    {
        return $user->isAdmin();
    }
}
