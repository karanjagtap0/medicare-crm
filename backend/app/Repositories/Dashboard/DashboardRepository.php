<?php

namespace App\Repositories\Dashboard;

use App\Interfaces\Dashboard\DashboardRepositoryInterface;
use App\Models\User;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function getMetrics(): array
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::where('status', 1)->count(),
            'inactive_users' => User::where('status', 0)->count(),
        ];
    }

    public function getRecentUsers(int $limit = 5)
    {
        return User::orderBy('created_at', 'desc')->take($limit)->get();
    }
}
