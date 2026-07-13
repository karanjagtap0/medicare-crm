<?php

namespace App\Services\Category;

use App\Interfaces\Category\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryService
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function getCategories(array $filters)
    {
        return $this->categoryRepository->getCategories($filters);
    }

    public function createCategory(array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        }
        
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->categoryRepository->createCategory($data);
    }

    public function getCategory(int $id)
    {
        return $this->categoryRepository->getCategory($id);
    }

    public function updateCategory(int $id, array $data)
    {
        if (isset($data['name'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $id);
        }
        
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->categoryRepository->updateCategory($id, $data);
    }

    public function updateStatus(int $id, bool $status)
    {
        $data = ['status' => $status];
        
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->categoryRepository->updateCategory($id, $data);
    }

    public function deleteCategory(int $id)
    {
        return $this->categoryRepository->deleteCategory($id);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        while (Category::where('slug', $slug)->when($ignoreId, function ($query) use ($ignoreId) {
            return $query->where('id', '!=', $ignoreId);
        })->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }
        
        return $slug;
    }
}
