<?php

namespace App\Repositories\Medicine;

use App\Interfaces\Medicine\MedicineBatchRepositoryInterface;
use App\Models\MedicineBatch;

class MedicineBatchRepository implements MedicineBatchRepositoryInterface
{
    public function getMedicineBatches(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        return MedicineBatch::query()
            ->with(['medicine'])
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where('batch_number', 'like', "%{$search}%")
                      ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(!empty($filters['medicine_id']), function ($query) use ($filters) {
                $query->where('medicine_id', $filters['medicine_id']);
            })
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function createMedicineBatch(array $data)
    {
        return MedicineBatch::create($data);
    }

    public function getMedicineBatch(int $id)
    {
        return MedicineBatch::with(['medicine'])->findOrFail($id);
    }

    public function updateMedicineBatch(int $id, array $data)
    {
        $batch = MedicineBatch::findOrFail($id);
        $batch->update($data);
        return $batch;
    }

    public function deleteMedicineBatch(int $id)
    {
        $batch = MedicineBatch::findOrFail($id);
        return $batch->delete();
    }
}
