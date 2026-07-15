<?php

namespace App\Services\Crm;

use App\Interfaces\Crm\CustomerFollowupRepositoryInterface;

class CustomerFollowupService
{
    public function __construct(private CustomerFollowupRepositoryInterface $customerFollowupRepository)
    {
    }

    public function getFollowups(array $filters)
    {
        return $this->customerFollowupRepository->getFollowups($filters);
    }

    public function getFollowup(int $id)
    {
        return $this->customerFollowupRepository->getFollowup($id);
    }

    public function createFollowup(array $data)
    {
        return $this->customerFollowupRepository->createFollowup($data);
    }

    public function updateFollowup(int $id, array $data)
    {
        return $this->customerFollowupRepository->updateFollowup($id, $data);
    }

    public function deleteFollowup(int $id)
    {
        return $this->customerFollowupRepository->deleteFollowup($id);
    }
}
