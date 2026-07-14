<?php

namespace App\Services\Medicine;

use App\Interfaces\Medicine\MedicineRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\Medicine;

class MedicineService
{
    public function __construct(private MedicineRepositoryInterface $medicineRepository)
    {
    }

    public function getMedicines(array $filters)
    {
        return $this->medicineRepository->getMedicines($filters);
    }

    public function createMedicine(array $data)
    {
        if (empty($data['medicine_code'])) {
            $data['medicine_code'] = $this->generateUniqueCode('MED');
        }
        
        if (empty($data['sku'])) {
            $data['sku'] = $this->generateUniqueSku('SKU');
        }
        
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->medicineRepository->createMedicine($data);
    }

    public function getMedicine(int $id)
    {
        return $this->medicineRepository->getMedicine($id);
    }

    public function updateMedicine(int $id, array $data)
    {
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->medicineRepository->updateMedicine($id, $data);
    }

    public function updateStatus(int $id, bool $status)
    {
        $data = ['status' => $status];
        
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->medicineRepository->updateMedicine($id, $data);
    }

    public function deleteMedicine(int $id)
    {
        return $this->medicineRepository->deleteMedicine($id);
    }

    private function generateUniqueCode(string $prefix): string
    {
        $lastRecord = Medicine::orderBy('id', 'desc')->first();
        $number = $lastRecord ? $lastRecord->id + 1 : 1;
        
        $code = $prefix . '-' . str_pad((string)$number, 5, '0', STR_PAD_LEFT);
        
        while (Medicine::where('medicine_code', $code)->exists()) {
            $number++;
            $code = $prefix . '-' . str_pad((string)$number, 5, '0', STR_PAD_LEFT);
        }
        
        return $code;
    }
    
    private function generateUniqueSku(string $prefix): string
    {
        $lastRecord = Medicine::orderBy('id', 'desc')->first();
        $number = $lastRecord ? $lastRecord->id + 1 : 1;
        
        $sku = $prefix . '-' . str_pad((string)$number, 6, '0', STR_PAD_LEFT);
        
        while (Medicine::where('sku', $sku)->exists()) {
            $number++;
            $sku = $prefix . '-' . str_pad((string)$number, 6, '0', STR_PAD_LEFT);
        }
        
        return $sku;
    }
}
