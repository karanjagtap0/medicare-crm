<?php

namespace App\Repositories\Brand;

use App\Interfaces\Brand\BrandRepositoryInterface;
use App\Models\Brand;

class BrandRepository implements BrandRepositoryInterface
{
    public function getBrands(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        return Brand::query()
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
            })
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function createBrand(array $data)
    {
        return Brand::create($data);
    }

    public function getBrand(int $id)
    {
        return Brand::findOrFail($id);
    }

    public function updateBrand(int $id, array $data)
    {
        $brand = Brand::findOrFail($id);
        $brand->update($data);
        return $brand;
    }

    public function deleteBrand(int $id)
    {
        $brand = Brand::findOrFail($id);
        return $brand->delete();
    }
}
