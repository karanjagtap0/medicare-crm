<?php

namespace App\Repositories\Tax;

use App\Interfaces\Tax\TaxRepositoryInterface;
use App\Models\Tax;

class TaxRepository implements TaxRepositoryInterface
{
    public function getTaxes(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        return Tax::query()
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
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

    public function createTax(array $data)
    {
        return Tax::create($data);
    }

    public function getTax(int $id)
    {
        return Tax::findOrFail($id);
    }

    public function updateTax(int $id, array $data)
    {
        $tax = Tax::findOrFail($id);
        $tax->update($data);
        return $tax;
    }

    public function deleteTax(int $id)
    {
        $tax = Tax::findOrFail($id);
        return $tax->delete();
    }
}
