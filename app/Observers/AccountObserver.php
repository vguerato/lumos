<?php

namespace App\Observers;

use App\Models\Account;
use App\Solutions\User\UserCache;

class AccountObserver
{
    public bool $afterCommit = true;

    public function created(Account $account): void
    {
        UserCache::warm($account->user);
    }

    public function deleted(Account $account): void
    {
        UserCache::warm($account->user);
    }
}
