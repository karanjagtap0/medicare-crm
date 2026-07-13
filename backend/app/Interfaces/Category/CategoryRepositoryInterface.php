<?php

namespace App\Interfaces\Category;

interface CategoryRepositoryInterface
{
    public function getCategories(array $filters);
    public function createCategory(array $data);
    public function getCategory(int $id);
    public function updateCategory(int $id, array $data);
    public function deleteCategory(int $id);
}
