<?php

namespace App\Repositories\Inventory;

use App\Interfaces\Inventory\InventoryRepositoryInterface;
use App\Models\MedicineStock;
use App\Models\StockTransaction;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\InventoryAudit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function getDashboardStats()
    {
        $totalItems = Medicine::count();
        $totalStockValue = MedicineStock::join('medicine_batches', 'medicine_stocks.medicine_batch_id', '=', 'medicine_batches.id')
            ->sum(DB::raw('medicine_stocks.current_quantity * medicine_batches.selling_price'));
        
        $lowStockItems = MedicineStock::where('current_quantity', '<=', 10)->count();
        $outOfStockItems = MedicineStock::where('current_quantity', 0)->count();
        
        $expiringSoon = MedicineBatch::whereDate('expiry_date', '<=', Carbon::now()->addDays(90))
            ->whereDate('expiry_date', '>=', Carbon::now())
            ->count();
            
        $expiredItems = MedicineBatch::whereDate('expiry_date', '<', Carbon::now())->count();

        return compact('totalItems', 'totalStockValue', 'lowStockItems', 'outOfStockItems', 'expiringSoon', 'expiredItems');
    }

    public function getCurrentStock(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        $query = MedicineStock::with(['medicine.category', 'medicine.brand', 'medicineBatch']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('medicine', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('medicine_code', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category_id'])) {
            $query->whereHas('medicine', function($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            });
        }

        if (!empty($filters['low_stock'])) {
            $query->where('current_quantity', '<=', 10);
        }

        if (isset($filters['expired'])) {
            $isExpired = (bool) $filters['expired'];
            $query->whereHas('medicineBatch', function($q) use ($isExpired) {
                if ($isExpired) {
                    $q->whereDate('expiry_date', '<', Carbon::now());
                } else {
                    $q->whereDate('expiry_date', '>=', Carbon::now());
                }
            });
        }

        return $query->paginate($perPage);
    }

    public function getStockLedger(array $filters)
    {
        $perPage = $filters['per_page'] ?? 20;
        
        $query = StockTransaction::with(['medicine', 'medicineBatch', 'user']);

        if (!empty($filters['medicine_id'])) {
            $query->where('medicine_id', $filters['medicine_id']);
        }

        if (!empty($filters['batch_id'])) {
            $query->where('medicine_batch_id', $filters['batch_id']);
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getInventoryTransactions(array $filters)
    {
        $perPage = $filters['per_page'] ?? 20;
        
        $query = StockTransaction::with(['medicine', 'medicineBatch', 'user']);

        if (!empty($filters['type'])) {
            $query->where('transaction_type', $filters['type']);
        }

        if (!empty($filters['reference'])) {
            $query->where('reference_no', 'like', "%{$filters['reference']}%");
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function createStockAdjustment(array $data)
    {
        return DB::transaction(function () use ($data) {
            $stock = MedicineStock::firstOrCreate(
                ['medicine_id' => $data['medicine_id'], 'medicine_batch_id' => $data['medicine_batch_id']],
                ['current_quantity' => 0]
            );

            if ($data['type'] === 'Reduction' && $stock->current_quantity < $data['quantity']) {
                throw new \Exception('Insufficient stock for reduction');
            }

            $oldQuantity = $stock->current_quantity;

            if ($data['type'] === 'Addition') {
                $stock->current_quantity += $data['quantity'];
            } else {
                $stock->current_quantity -= $data['quantity'];
            }
            
            $stock->save();

            StockTransaction::create([
                'medicine_id' => $data['medicine_id'],
                'medicine_batch_id' => $data['medicine_batch_id'],
                'transaction_type' => 'Adjustment',
                'quantity' => $data['quantity'],
                'previous_stock' => $oldQuantity,
                'current_stock' => $stock->current_quantity,
                'reference_no' => 'ADJ-' . time(),
                'remarks' => $data['reason'] . ' (' . $data['type'] . ')',
                'created_by' => Auth::id(),
            ]);

            return $stock;
        });
    }

    public function createStockTransfer(array $data)
    {
        return DB::transaction(function () use ($data) {
            $stock = MedicineStock::firstOrCreate(
                ['medicine_id' => $data['medicine_id'], 'medicine_batch_id' => $data['medicine_batch_id']],
                ['current_quantity' => 0]
            );

            if ($stock->current_quantity < $data['quantity']) {
                throw new \Exception('Insufficient stock for transfer');
            }

            $oldQuantity = $stock->current_quantity;
            $stock->current_quantity -= $data['quantity'];
            $stock->save();

            StockTransaction::create([
                'medicine_id' => $data['medicine_id'],
                'medicine_batch_id' => $data['medicine_batch_id'],
                'transaction_type' => 'Transfer',
                'quantity' => $data['quantity'],
                'previous_stock' => $oldQuantity,
                'current_stock' => $stock->current_quantity,
                'reference_no' => 'TRF-OUT-' . time(),
                'remarks' => "Transferred to {$data['to_location']}",
                'created_by' => Auth::id(),
            ]);

            return $stock;
        });
    }

    public function createInventoryAudit(array $data)
    {
        return DB::transaction(function () use ($data) {
            $stock = MedicineStock::firstOrCreate(
                ['medicine_id' => $data['medicine_id'], 'medicine_batch_id' => $data['medicine_batch_id']],
                ['current_quantity' => 0]
            );

            $systemQuantity = $stock->current_quantity;
            $variance = $data['physical_quantity'] - $systemQuantity;

            $audit = InventoryAudit::create([
                'medicine_id' => $data['medicine_id'],
                'medicine_batch_id' => $data['medicine_batch_id'],
                'system_stock' => $systemQuantity,
                'physical_stock' => $data['physical_quantity'],
                'difference' => $variance,
                'remarks' => $data['remarks'] ?? null,
                'created_by' => Auth::id(),
            ]);

            if ($variance !== 0) {
                $stock->current_quantity = $data['physical_quantity'];
                $stock->save();

                StockTransaction::create([
                    'medicine_id' => $data['medicine_id'],
                    'medicine_batch_id' => $data['medicine_batch_id'],
                    'transaction_type' => 'Adjustment',
                    'quantity' => abs($variance),
                    'previous_stock' => $systemQuantity,
                    'current_stock' => $data['physical_quantity'],
                    'reference_no' => 'AUDIT-' . $audit->id,
                    'remarks' => "Inventory Audit Variance",
                    'created_by' => Auth::id(),
                ]);
            }

            return $audit;
        });
    }

    public function getInventoryValuation(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        $query = MedicineStock::with(['medicine.category', 'medicineBatch'])
            ->join('medicine_batches', 'medicine_stocks.medicine_batch_id', '=', 'medicine_batches.id')
            ->select('medicine_stocks.*', DB::raw('(medicine_stocks.current_quantity * medicine_batches.purchase_price) as total_purchase_value'), DB::raw('(medicine_stocks.current_quantity * medicine_batches.selling_price) as total_mrp_value'));

        if (!empty($filters['category_id'])) {
            $query->whereHas('medicine', function($q) use ($filters) {
                $q->where('category_id', $filters['category_id']);
            });
        }

        return $query->paginate($perPage);
    }
}
