<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Medicine\ExpiryService;
use Illuminate\Http\Request;

class ExpiryController extends Controller
{
    public function __construct(private ExpiryService $expiryService)
    {
    }

    public function dashboard(Request $request)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $summary = $this->expiryService->getDashboardSummary();

        return response()->json([
            'success' => true,
            'message' => 'Expiry dashboard summary retrieved successfully.',
            'data' => $summary,
        ], 200);
    }

    public function expired(Request $request)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $batches = $this->expiryService->getExpiredBatches($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Expired batches retrieved successfully.',
            'data' => $batches,
        ], 200);
    }

    public function expiring(Request $request)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $batches = $this->expiryService->getExpiringBatches($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Expiring batches retrieved successfully.',
            'data' => $batches,
        ], 200);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $batch = $this->expiryService->getBatchDetails($id);

        return response()->json([
            'success' => true,
            'message' => 'Batch details retrieved successfully.',
            'data' => $batch,
        ], 200);
    }

    public function block(Request $request, $id)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $batch = $this->expiryService->blockBatch($id);

        return response()->json([
            'success' => true,
            'message' => 'Batch blocked successfully.',
            'data' => $batch,
        ], 200);
    }
}
