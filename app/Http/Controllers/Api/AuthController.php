<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register(
            $request->validated()
        );

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Пользователь успешно зарегистрирован.',
            'accessToken' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login(
            $request->validated('email'),
            $request->validated('password')
        );

        return response()->json([
            'message' => 'Авторизация успешна.',
            'accessToken' => $token,
        ]);
    }
}