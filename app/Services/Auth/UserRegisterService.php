<?php

namespace App\Services\Auth;

use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

readonly class UserRegisterService
{
    public function __construct(
        private UserRepository $userRepository
    )
    {}

    public function register(array $data): array
    {
        $user = $this->userRepository->createUser([
            'name' => $data['name'],
            'email'=> $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api')->plainTextToken;
        Auth::login($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
