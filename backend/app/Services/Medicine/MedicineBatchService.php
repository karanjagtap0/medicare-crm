<?php

namespace App\Services\Medicine;

use App\Interfaces\Medicine\MedicineBatchRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\MedicineBatch;

class MedicineBatchService
{
    public function __construct(private MedicineBatchRepositoryInterface $medicineBatchRepository)
    {
    }

    public function getMedicineBatches(array $filters)
    {
        return $this->medicineBatchRepository->getMedicineBatches($filters);
    }

    public function createMedicineBatch(array $data)
    {
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        if (!isset($data['available_quantity'])) {
            $data['available_quantity'] = $data['quantity_received'];
        }
        
        return $this->medicineBatchRepository->createMedicineBatch($data);
    }

    public function getMedicineBatch(int $id)
    {
        return $this->medicineBatchRepository->getMedicineBatch($id);
    }

    public function updateMedicineBatch(int $id, array $data)
    {
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->medicineBatchRepository->updateMedicineBatch($id, $data);
    }

    public function deleteMedicineBatch(int $id)
    {
        return $this->medicineBatchRepository->deleteMedicineBatch($id);
    }
}
