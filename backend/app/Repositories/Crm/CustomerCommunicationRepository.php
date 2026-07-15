<?php

namespace App\Repositories\Crm;

use App\Interfaces\Crm\CustomerCommunicationRepositoryInterface;
use App\Models\CustomerCommunication;

class CustomerCommunicationRepository implements CustomerCommunicationRepositoryInterface
{
    public function getCommunications(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        $query = CustomerCommunication::with('customer');
        
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getCommunication(int $id)
    {
        return CustomerCommunication::with('customer')->findOrFail($id);
    }

    public function createCommunication(array $data)
    {
        // In a real scenario, this is where you'd hook into Mail or SMS facades to actually send it.
        // For now, we assume it's created and maybe processed by a queue job.
        return CustomerCommunication::create($data);
    }
}
