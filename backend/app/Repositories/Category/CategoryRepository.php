<?php

namespace App\Repositories\Category;

use App\Interfaces\Category\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getCategories(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        return Category::query()
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

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function getCategory(int $id)
    {
        return Category::findOrFail($id);
    }

    public function updateCategory(int $id, array $data)
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        return $category;
    }

    public function deleteCategory(int $id)
    {
        $category = Category::findOrFail($id);
        return $category->delete();
    }
}
