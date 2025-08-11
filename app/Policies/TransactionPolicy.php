<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\Concerns\HasOwnership;
use App\Solutions\User\UserCache;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization, HasOwnership;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Transaction $transaction): bool
    {
        return $this->hasOwnership($user, $transaction->account_id);
    }

    public function create(User $user, string $addressId): bool
    {
        return $this->hasOwnership($user, $addressId);
    }

    public function transfer(User $user, string $addressId): bool
    {
        return $this->hasOwnership($user, $addressId);
    }
}
