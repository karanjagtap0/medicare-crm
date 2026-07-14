<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\AuthRepositoryInterface;
use App\Traits\ApiResponse;

class AuthService
{
    use ApiResponse;

    protected AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $data)
    {
        $user = $this->authRepository->register($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success('Registration successful.', [
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(array $data)
    {
        $response = $this->authRepository->login($data);

        return $this->success('Login successful.', $response);
    }

    public function profile()
    {
        return $this->success(
            'Profile fetched successfully.',
            $this->authRepository->profile()
        );
    }

    public function logout()
    {
        $this->authRepository->logout();

        return $this->success('Logout successful.');
    }
}
