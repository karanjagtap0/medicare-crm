<?php

namespace App\Repositories\Crm;

use App\Interfaces\Crm\CustomerFollowupRepositoryInterface;
use App\Models\CustomerFollowup;

class CustomerFollowupRepository implements CustomerFollowupRepositoryInterface
{
    public function getFollowups(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        $query = CustomerFollowup::with('customer');
        
        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        return $query->orderBy('followup_date', 'asc')->paginate($perPage);
    }

    public function getFollowup(int $id)
    {
        return CustomerFollowup::findOrFail($id);
    }

    public function createFollowup(array $data)
    {
        return CustomerFollowup::create($data);
    }

    public function updateFollowup(int $id, array $data)
    {
        $followup = $this->getFollowup($id);
        $followup->update($data);
        return $followup;
    }

    public function deleteFollowup(int $id)
    {
        $followup = $this->getFollowup($id);
        return $followup->delete();
    }
}
