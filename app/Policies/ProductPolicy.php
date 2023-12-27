<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Traits\AdminActionTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;
    use AdminActionTrait;

    /**
     * Determine whether the user can view the product.
     */
    public function addCategory(User $user, Product $product): bool
    {
        return $user->id === $product->seller->id;
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function deleteCategory(User $user, Product $product): bool
    {
        return $user->id === $product->seller->id;
    }
}
