<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use App\Traits\AdminAction;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization, AdminAction;

    /**
     * Determine whether the user can view the transaction.
     *
     * @param User        $user
     * @param Transaction $transaction
     * @return bool
     */
    public function view(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->buyer->id || $user->id === $transaction->product->seller->id;
    }
}
