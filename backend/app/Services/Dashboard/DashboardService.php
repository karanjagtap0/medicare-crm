<?php

namespace App\Services\Dashboard;

use App\Interfaces\Dashboard\DashboardRepositoryInterface;

class DashboardService
{
    public function __construct(private DashboardRepositoryInterface $dashboardRepository)
    {
    }

    public function getDashboardData(): array
    {
        return [
            'metrics' => $this->dashboardRepository->getMetrics(),
            'recent_users' => $this->dashboardRepository->getRecentUsers(),
        ];
    }
}
