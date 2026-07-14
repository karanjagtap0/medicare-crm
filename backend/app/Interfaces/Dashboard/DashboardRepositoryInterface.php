<?php

namespace App\Interfaces\Dashboard;

interface DashboardRepositoryInterface
{
    public function getMetrics(): array;
    public function getRecentUsers(int $limit = 5);
}
