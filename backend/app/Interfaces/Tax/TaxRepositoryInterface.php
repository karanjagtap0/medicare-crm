<?php

namespace App\Interfaces\Tax;

interface TaxRepositoryInterface
{
    public function getTaxes(array $filters);
    public function createTax(array $data);
    public function getTax(int $id);
    public function updateTax(int $id, array $data);
    public function deleteTax(int $id);
}
