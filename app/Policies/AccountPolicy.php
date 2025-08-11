<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\Concerns\HasOwnership;
use App\Solutions\User\UserCache;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization, HasOwnership;

    public function view(User $user, string $accountId): bool
    {
        return $this->hasOwnership($user, $accountId);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, string $accountId): bool
    {
        return $this->hasOwnership($user, $accountId);
    }
}
