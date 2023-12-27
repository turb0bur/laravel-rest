<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\User;
use App\Traits\AdminActionTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellerPolicy
{
    use HandlesAuthorization;
    use AdminActionTrait;

    /**
     * Determine whether the user can view the seller.
     */
    public function view(User $user, Seller $seller): bool
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can sale.
     */
    public function sale(User $user, Seller $seller): bool
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can update a product.
     */
    public function editProduct(User $user, Seller $seller): bool
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can delete a product.
     */
    public function deleteProduct(User $user, Seller $seller): bool
    {
        return $user->id === $seller->id;
    }
}
