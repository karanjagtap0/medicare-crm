<?php

namespace App\Services\Crm;

use App\Interfaces\Crm\CustomerCommunicationRepositoryInterface;

class CustomerCommunicationService
{
    public function __construct(private CustomerCommunicationRepositoryInterface $customerCommunicationRepository)
    {
    }

    public function getCommunications(array $filters)
    {
        return $this->customerCommunicationRepository->getCommunications($filters);
    }

    public function getCommunication(int $id)
    {
        return $this->customerCommunicationRepository->getCommunication($id);
    }

    public function createCommunication(array $data)
    {
        return $this->customerCommunicationRepository->createCommunication($data);
    }
}
