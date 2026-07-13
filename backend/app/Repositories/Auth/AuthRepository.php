<?php

namespace App\Repositories\Auth;

use App\Interfaces\Auth\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'dob' => $data['dob'] ?? null,
            'email' => $data['email'],
            'mobile_no' => $data['mobile_no'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'zip_code' => $data['zip_code'] ?? null,
            'weight' => $data['weight'] ?? null,
            'password' => Hash::make($data['password']),
            'status' => $data['status'],
        ]);
    }

    public function login(array $data): array
    {
        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function profile(): User
    {
        return Auth::user();
    }

    public function logout(): bool
    {
        Auth::user()->currentAccessToken()->delete();

        return true;
    }
}
