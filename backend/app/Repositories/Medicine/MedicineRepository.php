<?php

namespace App\Repositories\Medicine;

use App\Interfaces\Medicine\MedicineRepositoryInterface;
use App\Models\Medicine;

class MedicineRepository implements MedicineRepositoryInterface
{
    public function getMedicines(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        return Medicine::query()
            ->with(['category', 'brand', 'supplier', 'tax', 'unitOfMeasure'])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->bySearch($filters['search']);
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(!empty($filters['category_id']), function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            })
            ->when(!empty($filters['brand_id']), function ($query) use ($filters) {
                $query->where('brand_id', $filters['brand_id']);
            })
            ->when(!empty($filters['supplier_id']), function ($query) use ($filters) {
                $query->where('supplier_id', $filters['supplier_id']);
            })
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function createMedicine(array $data)
    {
        return Medicine::create($data);
    }

    public function getMedicine(int $id)
    {
        return Medicine::with(['category', 'brand', 'supplier', 'tax', 'unitOfMeasure'])->findOrFail($id);
    }

    public function updateMedicine(int $id, array $data)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->update($data);
        return $medicine;
    }

    public function deleteMedicine(int $id)
    {
        $medicine = Medicine::findOrFail($id);
        return $medicine->delete();
    }
}
