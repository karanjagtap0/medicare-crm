<?php

namespace App\Repositories\Supplier;

use App\Interfaces\Supplier\SupplierRepositoryInterface;
use App\Models\Supplier;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function getSuppliers(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        return Supplier::query()
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where('company_name', 'like', "%{$search}%")
                      ->orWhere('supplier_code', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function createSupplier(array $data)
    {
        return Supplier::create($data);
    }

    public function getSupplier(int $id)
    {
        return Supplier::findOrFail($id);
    }

    public function updateSupplier(int $id, array $data)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($data);
        return $supplier;
    }

    public function deleteSupplier(int $id)
    {
        $supplier = Supplier::findOrFail($id);
        return $supplier->delete();
    }
}
