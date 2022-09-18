<?php

namespace App\Policies;

use App\Buyer;
use App\Traits\AdminAction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuyerPolicy
{
    use HandlesAuthorization, AdminAction;

    /**
     * Determine whether the user can view the buyer.
     *
     * @param \App\User  $user
     * @param \App\Buyer $buyer
     * @return bool
     */
    public function view(User $user, Buyer $buyer)
    {
        return $user->id === $buyer->id;
    }

    /**
     * Determine whether the user can purchase the product.
     *
     * @param \App\User  $user
     * @param \App\Buyer $buyer
     * @return bool
     */
    public function purchase(User $user, Buyer $buyer)
    {
        return $user->id === $buyer->id;
    }
}
