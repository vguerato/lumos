<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Validation\UnauthorizedException;

readonly class UserLoginService
{
    public function __construct(private UserRepository $userRepository)
    {
        //
    }

    public function login(array $data): array
    {
        $user = $this->userRepository->getUserByEmail($data['email']);
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new UnauthorizedException('Invalid credentials', 401);
        }

        $token = $user->createToken('api')->plainTextToken;
        Auth::login($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
