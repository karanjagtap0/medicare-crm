<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Stock\StockService;
use App\Http\Requests\Stock\StockTransactionRequest;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(private StockService $stockService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $stocks = $this->stockService->getCurrentStock($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Current stock retrieved successfully.',
            'data' => $stocks,
        ], 200);
    }

    public function medicineStock(Request $request, $medicine)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $stocks = $this->stockService->getMedicineStock((int) $medicine);

        return response()->json([
            'success' => true,
            'message' => 'Medicine stock retrieved successfully.',
            'data' => $stocks,
        ], 200);
    }

    public function history(Request $request)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $history = $this->stockService->getStockHistory($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Stock history retrieved successfully.',
            'data' => $history,
        ], 200);
    }

    public function lowStock(Request $request)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $lowStock = $this->stockService->getLowStock($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Low stock medicines retrieved successfully.',
            'data' => $lowStock,
        ], 200);
    }

    public function dashboard(Request $request)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $summary = $this->stockService->getDashboardSummary();

        return response()->json([
            'success' => true,
            'message' => 'Stock dashboard summary retrieved successfully.',
            'data' => $summary,
        ], 200);
    }

    public function stockIn(StockTransactionRequest $request)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $transaction = $this->stockService->handleTransaction($request->validated(), 'Stock In');
            return response()->json([
                'success' => true,
                'message' => 'Stock In recorded successfully.',
                'data' => $transaction,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function stockOut(StockTransactionRequest $request)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $transaction = $this->stockService->handleTransaction($request->validated(), 'Stock Out');
            return response()->json([
                'success' => true,
                'message' => 'Stock Out recorded successfully.',
                'data' => $transaction,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function adjustment(StockTransactionRequest $request)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $transaction = $this->stockService->handleTransaction($request->validated(), 'Adjustment');
            return response()->json([
                'success' => true,
                'message' => 'Stock adjustment recorded successfully.',
                'data' => $transaction,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function transfer(StockTransactionRequest $request)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $transaction = $this->stockService->handleTransaction($request->validated(), 'Transfer');
            return response()->json([
                'success' => true,
                'message' => 'Stock transfer recorded successfully.',
                'data' => $transaction,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
