<?php

namespace App\Services\User;

use App\Interfaces\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function getUserList(array $filters)
    {
        return $this->userRepository->UserLists($filters);
    }

    public function createUser(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->userRepository->Users($data);
    }

    public function getUser(int $id)
    {
        return $this->userRepository->GetUser($id);
    }

    public function updateUser(int $id, array $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        return $this->userRepository->UpdateUser($id, $data);
    }

    public function deleteUser(int $id)
    {
        return $this->userRepository->DeleteUser($id);
    }
}
