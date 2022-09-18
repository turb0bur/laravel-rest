<?php

namespace App\Policies;

use App\Seller;
use App\Traits\AdminAction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerPolicy
{
    use HandlesAuthorization, AdminAction;

    /**
     * Determine whether the user can view the seller.
     *
     * @param \App\User   $user
     * @param \App\Seller $seller
     * @return bool
     */
    public function view(User $user, Seller $seller)
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can sale.
     *
     * @param \App\User   $user
     * @param \App\Seller $seller
     * @return bool
     */
    public function sale(User $user, Seller $seller)
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can update a product.
     *
     * @param \App\User   $user
     * @param \App\Seller $seller
     * @return bool
     */
    public function editProduct(User $user, Seller $seller)
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can delete a product.
     *
     * @param \App\User   $user
     * @param \App\Seller $seller
     * @return bool
     */
    public function deleteProduct(User $user, Seller $seller)
    {
        return $user->id === $seller->id;
    }
}
