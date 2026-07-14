<?php

namespace App\Services\Brand;

use App\Interfaces\Brand\BrandRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Brand;

class BrandService
{
    public function __construct(private BrandRepositoryInterface $brandRepository)
    {
    }

    public function getBrands(array $filters)
    {
        return $this->brandRepository->getBrands($filters);
    }

    public function createBrand(array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        }
        
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->brandRepository->createBrand($data);
    }

    public function getBrand(int $id)
    {
        return $this->brandRepository->getBrand($id);
    }

    public function updateBrand(int $id, array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $id);
        }
        
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->brandRepository->updateBrand($id, $data);
    }

    public function updateStatus(int $id, bool $status)
    {
        $data = ['status' => $status];
        
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->brandRepository->updateBrand($id, $data);
    }

    public function deleteBrand(int $id)
    {
        return $this->brandRepository->deleteBrand($id);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        while (Brand::where('slug', $slug)->when($ignoreId, function ($query) use ($ignoreId) {
            return $query->where('id', '!=', $ignoreId);
        })->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        
        return $slug;
    }
}
