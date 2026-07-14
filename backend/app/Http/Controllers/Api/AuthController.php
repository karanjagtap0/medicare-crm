<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        return $this->authService->register(
            $request->validated()
        );
    }

    public function login(LoginRequest $request)
    {
        return $this->authService->login(
            $request->validated()
        );
    }

    public function profile()
    {
        return $this->authService->profile();
    }

    public function logout()
    {
        return $this->authService->logout();
    }
}
