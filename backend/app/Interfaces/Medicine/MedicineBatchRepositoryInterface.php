<?php

namespace App\Interfaces\Medicine;

interface MedicineBatchRepositoryInterface
{
    public function getMedicineBatches(array $filters);
    public function createMedicineBatch(array $data);
    public function getMedicineBatch(int $id);
    public function updateMedicineBatch(int $id, array $data);
    public function deleteMedicineBatch(int $id);
}
