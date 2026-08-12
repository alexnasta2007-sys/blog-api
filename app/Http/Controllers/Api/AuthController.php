<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = $this->authService->register($data);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Пользователь успешно зарегистрирован.',
            'accessToken' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $token = $this->authService->login(
            $data['email'],
            $data['password']
        );

        return response()->json([
            'message' => 'Авторизация успешна.',
            'accessToken' => $token,
        ]);
    }
}