<?php

namespace App\Repositories\User;

use App\Interfaces\User\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function UserLists(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;

        return User::query()
        ->when(!empty($filters['search']), function ($query) use ($filters) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile_no', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%");
            });
        })

        ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
            $query->where('status', $filters['status']);
        })

        ->when(!empty($filters['city']), function ($query) use ($filters) {
            $query->where('city', $filters['city']);
        })

        ->when(!empty($filters['state']), function ($query) use ($filters) {
            $query->where('state', $filters['state']);
        })

        ->when(!empty($filters['dob']), function ($query) use ($filters) {
            $query->whereDate('dob', $filters['dob']);
        })

        ->orderByDesc('id')
        ->paginate($perPage);

    }

    public function Users(array $data)
    {
        return User::create($data);
    }

    public function GetUser(int $id)
    {
        return User::findOrFail($id);
    }

    public function UpdateUser(int $id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function DeleteUser(int $id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
