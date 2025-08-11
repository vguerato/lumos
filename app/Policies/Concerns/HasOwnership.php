<?php

namespace App\Policies\Concerns;

use App\Models\User;
use App\Solutions\User\UserCache;

trait HasOwnership
{
    private function hasOwnership(User $user, string $accountId): bool
    {
        $cached = UserCache::get();
        $ids = $cached['accounts'] ?? null;

        if (is_array($ids) && $ids !== []) {
            return in_array($accountId, $ids, true);
        }

        return $user->accounts()->whereKey($accountId)->exists();
    }
}
