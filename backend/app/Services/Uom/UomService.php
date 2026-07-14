<?php

namespace App\Services\Uom;

use App\Interfaces\Uom\UomRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class UomService
{
    public function __construct(private UomRepositoryInterface $uomRepository)
    {
    }

    public function getUoms(array $filters)
    {
        return $this->uomRepository->getUoms($filters);
    }

    public function createUom(array $data)
    {
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->uomRepository->createUom($data);
    }

    public function getUom(int $id)
    {
        return $this->uomRepository->getUom($id);
    }

    public function updateUom(int $id, array $data)
    {
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->uomRepository->updateUom($id, $data);
    }

    public function updateStatus(int $id, bool $status)
    {
        $data = ['status' => $status];
        
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->uomRepository->updateUom($id, $data);
    }

    public function deleteUom(int $id)
    {
        return $this->uomRepository->deleteUom($id);
    }
}
