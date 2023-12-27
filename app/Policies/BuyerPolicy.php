<?php

namespace App\Policies;

use App\Models\Buyer;
use App\Models\User;
use App\Traits\AdminActionTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuyerPolicy
{
    use HandlesAuthorization;
    use AdminActionTrait;

    /**
     * Determine whether the user can view the buyer.
     */
    public function view(User $user, Buyer $buyer): bool
    {
        return $user->id === $buyer->id;
    }

    /**
     * Determine whether the user can purchase the product.
     */
    public function purchase(User $user, Buyer $buyer): bool
    {
        return $user->id === $buyer->id;
    }
}
