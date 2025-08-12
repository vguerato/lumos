<?php

namespace App\Solutions\User;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserCache
{
    public const TTL = 60 * 60 * 24;

    /**
     * @throws Exception
     */
    public static function key(): string
    {
        $userId = request()->user()?->id ?? Auth::id();

        if (!$userId) {
            throw new Exception('User not found');
        }

        return sprintf('user:%s', $userId);
    }

    /**
     * Pre-fetch user info for faster data transference between services.
     * @param User $user
     * @return array
     * @throws Exception
     */
    public static function warm(User $user): array
    {
        $accounts = $user->accounts()->pluck('id')->all();

        $payload = [
            ...$user->toArray(),
            'accounts' => $accounts,
        ];

        Cache::put(sprintf('user:%s', $user->id), $payload, self::TTL);

        return $payload;
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function get(): array
    {
        return Cache::get(self::key(), []);
    }

    /**
     * @param User|null $user
     * @return void
     * @throws Exception
     */
    public static function clear(?User $user = null): void
    {
        if ($user instanceof User) {
            Cache::forget(sprintf('user:%s', $user->id));
            return;
        }

        Cache::forget(self::key());
    }
}
