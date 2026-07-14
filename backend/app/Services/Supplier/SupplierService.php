<?php

namespace App\Services\Supplier;

use App\Interfaces\Supplier\SupplierRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;

class SupplierService
{
    public function __construct(private SupplierRepositoryInterface $supplierRepository)
    {
    }

    public function getSuppliers(array $filters)
    {
        return $this->supplierRepository->getSuppliers($filters);
    }

    public function createSupplier(array $data)
    {
        // Generate a unique supplier code if not provided
        if (empty($data['supplier_code'])) {
            $data['supplier_code'] = $this->generateUniqueSupplierCode();
        }
        
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->supplierRepository->createSupplier($data);
    }

    public function getSupplier(int $id)
    {
        return $this->supplierRepository->getSupplier($id);
    }

    public function updateSupplier(int $id, array $data)
    {
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->supplierRepository->updateSupplier($id, $data);
    }

    public function updateStatus(int $id, bool $status)
    {
        $data = ['status' => $status];
        
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->supplierRepository->updateSupplier($id, $data);
    }

    public function deleteSupplier(int $id)
    {
        return $this->supplierRepository->deleteSupplier($id);
    }

    private function generateUniqueSupplierCode(): string
    {
        $prefix = 'SUP';
        $lastSupplier = Supplier::orderBy('id', 'desc')->first();
        
        if (!$lastSupplier) {
            $number = 1;
        } else {
            // Try to extract the number from the last code, assuming format SUP-0001
            $parts = explode('-', $lastSupplier->supplier_code);
            if (count($parts) > 1 && is_numeric($parts[1])) {
                $number = (int)$parts[1] + 1;
            } else {
                $number = $lastSupplier->id + 1;
            }
        }
        
        $code = $prefix . '-' . str_pad((string)$number, 4, '0', STR_PAD_LEFT);
        
        // Ensure uniqueness
        while (Supplier::where('supplier_code', $code)->exists()) {
            $number++;
            $code = $prefix . '-' . str_pad((string)$number, 4, '0', STR_PAD_LEFT);
        }
        
        return $code;
    }
}
