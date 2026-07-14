<?php

namespace App\Interfaces\Brand;

interface BrandRepositoryInterface
{
    public function getBrands(array $filters);
    public function createBrand(array $data);
    public function getBrand(int $id);
    public function updateBrand(int $id, array $data);
    public function deleteBrand(int $id);
}
