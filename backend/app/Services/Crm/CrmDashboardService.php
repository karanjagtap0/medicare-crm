<?php

namespace App\Services\Crm;

use App\Interfaces\Crm\CrmDashboardRepositoryInterface;

class CrmDashboardService
{
    public function __construct(private CrmDashboardRepositoryInterface $crmDashboardRepository)
    {
    }

    public function getDashboardStats()
    {
        return $this->crmDashboardRepository->getDashboardStats();
    }
}
