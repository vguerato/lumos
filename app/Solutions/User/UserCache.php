<?php

namespace App\Solutions\User;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserCache
{
    public const TTL = 60 * 60 * 24;

    public static function key(): string
    {
        return sprintf('user:%s', request()->user()->id);
    }

    public static function warm(User $user): array
    {
        $accounts = $user->accounts()->pluck('id')->all();

        $payload = [
            ...$user->toArray(),
            'accounts' => $accounts,
        ];

        Cache::put(self::key(), $payload, self::TTL);

        return $payload;
    }

    public static function get(): array
    {
        return Cache::get(self::key(), []);
    }

    public static function clear(): void
    {
        Cache::forget(self::key());
    }
}
