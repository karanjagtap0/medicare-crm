<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('dashboard.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $dashboardData = $this->dashboardService->getDashboardData();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard data retrieved successfully.',
            'data' => $dashboardData
        ], 200);
    }
}
