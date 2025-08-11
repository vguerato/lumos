<?php

namespace App\Http\Controllers;

use App\Services\Auth\UserLoginService;
use App\Services\Auth\UserRegisterService;
use App\Services\Auth\UserLogoutService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    /**
     * POST /api/register
     * @param Request $request
     * @param UserRegisterService $userRegisterService
     * @return JsonResponse
     */
    public function register(Request $request, UserRegisterService $userRegisterService): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','unique:users,email'],
            'password' => ['required', Password::min(8)],
        ]);

        $registered = $userRegisterService->register($data);

        return response()->json($registered, 201);
    }

    /**
     * POST /api/login
     * @param Request $request
     * @param UserLoginService $userLoginService
     * @return JsonResponse
     */
    public function login(Request $request, UserLoginService $userLoginService): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $loggedUser = $userLoginService->login($data);

        return response()->json($loggedUser);
    }

    /**
     * POST /api/logout
     * @param Request $request
     * @param UserLogoutService $userLogoutService
     * @return JsonResponse
     */
    public function logout(Request $request, UserLogoutService $userLogoutService): JsonResponse
    {
        $userLogoutService->logout();
        return response()->json(['message' => 'Logged out']);
    }
}
