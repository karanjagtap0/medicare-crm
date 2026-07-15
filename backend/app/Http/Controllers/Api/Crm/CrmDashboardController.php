<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Services\Crm\CrmDashboardService;
use Illuminate\Http\Request;

class CrmDashboardController extends Controller
{
    public function __construct(private CrmDashboardService $crmDashboardService)
    {
    }

    public function dashboard(Request $request)
    {
        // Use generic dashboard.view permission or customer.view
        if (!$request->user()?->can('dashboard.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $stats = $this->crmDashboardService->getDashboardStats();

        return response()->json([
            'success' => true,
            'message' => 'CRM dashboard stats retrieved successfully.',
            'data' => $stats,
        ], 200);
    }
}
