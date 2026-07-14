<?php

namespace App\Interfaces\Uom;

interface UomRepositoryInterface
{
    public function getUoms(array $filters);
    public function createUom(array $data);
    public function getUom(int $id);
    public function updateUom(int $id, array $data);
    public function deleteUom(int $id);
}
