<?php

namespace App\Repositories\Uom;

use App\Interfaces\Uom\UomRepositoryInterface;
use App\Models\UnitOfMeasure;

class UomRepository implements UomRepositoryInterface
{
    public function getUoms(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        return UnitOfMeasure::query()
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('symbol', 'like', "%{$search}%");
            })
            ->when(!empty($filters['type']), function ($query) use ($filters) {
                $query->where('type', $filters['type']);
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function createUom(array $data)
    {
        return UnitOfMeasure::create($data);
    }

    public function getUom(int $id)
    {
        return UnitOfMeasure::findOrFail($id);
    }

    public function updateUom(int $id, array $data)
    {
        $uom = UnitOfMeasure::findOrFail($id);
        $uom->update($data);
        return $uom;
    }

    public function deleteUom(int $id)
    {
        $uom = UnitOfMeasure::findOrFail($id);
        return $uom->delete();
    }
}
