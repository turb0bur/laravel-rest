<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use App\Traits\AdminActionTrait;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;
    use AdminActionTrait;

    /**
     * Determine whether the user can view the transaction.
     *
     * @param User        $user
     * @param Transaction $transaction
     * @return bool
     */
    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->buyer->id || $user->id === $transaction->product->seller->id;
    }
}
