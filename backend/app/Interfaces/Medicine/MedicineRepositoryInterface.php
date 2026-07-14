<?php

namespace App\Interfaces\Medicine;

interface MedicineRepositoryInterface
{
    public function getMedicines(array $filters);
    public function createMedicine(array $data);
    public function getMedicine(int $id);
    public function updateMedicine(int $id, array $data);
    public function deleteMedicine(int $id);
}
