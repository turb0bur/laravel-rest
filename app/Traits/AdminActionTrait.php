<?php

namespace App\Traits;

use App\Models\User;

trait AdminActionTrait
{
    /**
     * Check before an action whether the authenticated user is an admin.
     */
    public function before(User $user): bool
    {
        return $user->isAdmin();
    }
}
