<?php

namespace App\Services\Auth;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Auth;

readonly class UserLogoutService
{
    public function logout(): void
    {
        if ($user = request()->user()) {
            $user->currentAccessToken()?->delete();
            Auth::logout();
        }
    }
}
