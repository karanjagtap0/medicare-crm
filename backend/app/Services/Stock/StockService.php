<?php

namespace App\Services\Stock;

use App\Models\MedicineStock;
use App\Models\MedicineBatch;
use App\Models\StockTransaction;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockService
{
    public function getCurrentStock(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        return MedicineStock::with(['medicine', 'medicineBatch'])
            ->when(!empty($filters['medicine_id']), function($q) use ($filters) {
                $q->where('medicine_id', $filters['medicine_id']);
            })
            ->when(!empty($filters['medicine_batch_id']), function($q) use ($filters) {
                $q->where('medicine_batch_id', $filters['medicine_batch_id']);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function getMedicineStock(int $medicineId)
    {
        return MedicineStock::with(['medicineBatch'])
            ->where('medicine_id', $medicineId)
            ->get();
    }

    public function getStockHistory(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        return StockTransaction::with(['medicine', 'medicineBatch', 'user'])
            ->when(!empty($filters['medicine_id']), function($q) use ($filters) {
                $q->where('medicine_id', $filters['medicine_id']);
            })
            ->when(!empty($filters['transaction_type']), function($q) use ($filters) {
                $q->where('transaction_type', $filters['transaction_type']);
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function getLowStock(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        // Find medicines where total stock is less than minimum_stock
        return Medicine::with(['category', 'brand'])
            ->where('status', true)
            ->whereRaw('(SELECT COALESCE(SUM(current_quantity), 0) FROM medicine_stocks WHERE medicine_stocks.medicine_id = medicines.id) <= minimum_stock')
            ->paginate($perPage);
    }

    public function getDashboardSummary()
    {
        $totalStockValue = MedicineBatch::where('status', 'Active')
            ->selectRaw('SUM(available_quantity * purchase_price) as total')
            ->value('total') ?? 0;
            
        $lowStockCount = Medicine::where('status', true)
            ->whereRaw('(SELECT COALESCE(SUM(current_quantity), 0) FROM medicine_stocks WHERE medicine_stocks.medicine_id = medicines.id) <= minimum_stock')
            ->count();
            
        $totalTransactionsToday = StockTransaction::whereDate('created_at', now()->toDateString())->count();
        
        return [
            'total_stock_value' => (float) $totalStockValue,
            'low_stock_medicines' => $lowStockCount,
            'transactions_today' => $totalTransactionsToday
        ];
    }

    public function handleTransaction(array $data, string $type)
    {
        return DB::transaction(function () use ($data, $type) {
            $medicineId = $data['medicine_id'];
            $batchId = $data['medicine_batch_id'];
            $quantity = (int) $data['quantity'];
            
            // Adjust quantity sign based on transaction type
            if ($type === 'Stock Out' || $type === 'Transfer') {
                $quantity = -$quantity;
            }
            
            // Get or create MedicineStock
            $stock = MedicineStock::firstOrCreate(
                ['medicine_id' => $medicineId, 'medicine_batch_id' => $batchId],
                ['current_quantity' => 0]
            );
            
            $previousStock = $stock->current_quantity;
            $currentStock = $previousStock + $quantity;
            
            if ($currentStock < 0) {
                throw new \Exception('Insufficient stock for this transaction.');
            }
            
            // Update MedicineStock
            $stock->update(['current_quantity' => $currentStock]);
            
            // Sync MedicineBatch available_quantity
            $batch = MedicineBatch::findOrFail($batchId);
            $batch->update(['available_quantity' => $currentStock]);
            
            // Create Transaction Record
            return StockTransaction::create([
                'medicine_id' => $medicineId,
                'medicine_batch_id' => $batchId,
                'transaction_type' => $type,
                'quantity' => abs($quantity), // store positive absolute value in transaction log usually, or exact? DB says integer, let's keep exact positive if requested, wait. 
                // Let's store the actual change amount or the absolute value? Usually Stock Out of 5 is quantity=5.
                'previous_stock' => $previousStock,
                'current_stock' => $currentStock,
                'reference_no' => $data['reference_no'] ?? null,
                'remarks' => $data['remarks'] ?? null,
                'created_by' => Auth::id()
            ]);
        });
    }
}
