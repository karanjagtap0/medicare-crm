<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(private InventoryService $inventoryService)
    {
    }

    public function dashboard(Request $request)
    {
        if (!$request->user()?->can('inventory.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $stats = $this->inventoryService->getDashboardStats();

        return response()->json([
            'success' => true,
            'message' => 'Inventory dashboard stats retrieved successfully.',
            'data' => $stats
        ], 200);
    }

    public function currentStock(Request $request)
    {
        if (!$request->user()?->can('inventory.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $stocks = $this->inventoryService->getCurrentStock($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Current stock retrieved successfully.',
            'data' => $stocks
        ], 200);
    }

    public function ledger(Request $request)
    {
        if (!$request->user()?->can('inventory.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $ledger = $this->inventoryService->getStockLedger($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Stock ledger retrieved successfully.',
            'data' => $ledger
        ], 200);
    }

    public function transactions(Request $request)
    {
        if (!$request->user()?->can('inventory.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $transactions = $this->inventoryService->getInventoryTransactions($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Inventory transactions retrieved successfully.',
            'data' => $transactions
        ], 200);
    }

    public function adjustment(Request $request)
    {
        if (!$request->user()?->can('inventory.adjust')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'medicine_batch_id' => 'required|exists:medicine_batches,id',
            'type' => 'required|in:Addition,Reduction',
            'quantity' => 'required|numeric|min:1',
            'reason' => 'required|string',
        ]);

        try {
            $stock = $this->inventoryService->createStockAdjustment($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Stock adjusted successfully.',
                'data' => $stock
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function transfer(Request $request)
    {
        if (!$request->user()?->can('inventory.transfer')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'medicine_batch_id' => 'required|exists:medicine_batches,id',
            'to_location' => 'required|string',
            'quantity' => 'required|numeric|min:1',
        ]);

        try {
            $stock = $this->inventoryService->createStockTransfer($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Stock transferred successfully.',
                'data' => $stock
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function audit(Request $request)
    {
        if (!$request->user()?->can('inventory.audit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'medicine_batch_id' => 'required|exists:medicine_batches,id',
            'physical_quantity' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        $audit = $this->inventoryService->createInventoryAudit($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Inventory audit recorded successfully.',
            'data' => $audit
        ], 201);
    }

    public function valuation(Request $request)
    {
        if (!$request->user()?->can('inventory.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $valuation = $this->inventoryService->getInventoryValuation($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Inventory valuation retrieved successfully.',
            'data' => $valuation
        ], 200);
    }
}
