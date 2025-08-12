<?php

namespace App\Listeners\Auth;

use App\Models\User;
use App\Solutions\User\UserCache;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log;

class UserCacheSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserAuthenticate(Login|Registered $event): void
    {
        Log::debug('UserCacheSubscriber:handleUserAuthenticate', $event->user->toArray());

        /** @var User $user */
        $user = $event->user;
        UserCache::warm($user);
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout(Logout $event): void
    {
        /** @var User $user */
        $user = $event->user;
        if ($user instanceof User) {
            UserCache::clear();
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            Login::class => 'handleUserAuthenticate',
            Registered::class => 'handleUserAuthenticate',
            Logout::class => 'handleUserLogout',
        ];
    }
}
