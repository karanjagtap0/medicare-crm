<?php

namespace App\Services\Customer;

use App\Interfaces\Customer\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CustomerService
{
    public function __construct(private CustomerRepositoryInterface $customerRepository)
    {
    }

    public function getCustomers(array $filters)
    {
        return $this->customerRepository->getCustomers($filters);
    }

    public function createCustomer(array $data)
    {
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->customerRepository->createCustomer($data);
    }

    public function getCustomer(int $id)
    {
        return $this->customerRepository->getCustomer($id);
    }

    public function updateCustomer(int $id, array $data)
    {
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->customerRepository->updateCustomer($id, $data);
    }

    public function updateStatus(int $id, bool $status)
    {
        $data = ['status' => $status];
        
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->customerRepository->updateCustomer($id, $data);
    }

    public function deleteCustomer(int $id)
    {
        return $this->customerRepository->deleteCustomer($id);
    }
}
