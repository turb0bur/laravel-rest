<?php

namespace App\Policies;

use App\Product;
use App\Traits\AdminAction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization, AdminAction;

    /**
     * Determine whether the user can view the product.
     *
     * @param User  $user
     * @param Product  $product
     * @return bool
     */
    public function addCategory(User $user, Product $product)
    {
        return $user->id === $product->seller->id;
    }

    /**
     * Determine whether the user can delete the product.
     *
     * @param User  $user
     * @param Product  $product
     * @return bool
     */
    public function deleteCategory(User $user, Product $product)
    {
        return $user->id === $product->seller->id;
    }
}
