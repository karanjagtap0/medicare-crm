<?php

namespace App\Services\Tax;

use App\Interfaces\Tax\TaxRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TaxService
{
    public function __construct(private TaxRepositoryInterface $taxRepository)
    {
    }

    public function getTaxes(array $filters)
    {
        return $this->taxRepository->getTaxes($filters);
    }

    public function createTax(array $data)
    {
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->taxRepository->createTax($data);
    }

    public function getTax(int $id)
    {
        return $this->taxRepository->getTax($id);
    }

    public function updateTax(int $id, array $data)
    {
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->taxRepository->updateTax($id, $data);
    }

    public function updateStatus(int $id, bool $status)
    {
        $data = ['status' => $status];
        
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->taxRepository->updateTax($id, $data);
    }

    public function deleteTax(int $id)
    {
        return $this->taxRepository->deleteTax($id);
    }
}
