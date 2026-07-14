<?php

namespace App\Interfaces\Supplier;

interface SupplierRepositoryInterface
{
    public function getSuppliers(array $filters);
    public function createSupplier(array $data);
    public function getSupplier(int $id);
    public function updateSupplier(int $id, array $data);
    public function deleteSupplier(int $id);
}
