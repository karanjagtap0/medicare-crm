<?php

namespace App\Services\Customer;

use App\Interfaces\Customer\CustomerAddressRepositoryInterface;

class CustomerAddressService
{
    public function __construct(private CustomerAddressRepositoryInterface $addressRepository)
    {
    }

    public function getCustomerAddresses(int $customerId)
    {
        return $this->addressRepository->getCustomerAddresses($customerId);
    }

    public function createCustomerAddress(int $customerId, array $data)
    {
        return $this->addressRepository->createCustomerAddress($customerId, $data);
    }

    public function updateCustomerAddress(int $id, array $data)
    {
        return $this->addressRepository->updateCustomerAddress($id, $data);
    }

    public function deleteCustomerAddress(int $id)
    {
        return $this->addressRepository->deleteCustomerAddress($id);
    }
}
