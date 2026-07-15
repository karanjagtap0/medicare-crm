<?php

namespace App\Interfaces\Customer;

interface CustomerAddressRepositoryInterface
{
    public function getCustomerAddresses(int $customerId);
    public function createCustomerAddress(int $customerId, array $data);
    public function updateCustomerAddress(int $id, array $data);
    public function deleteCustomerAddress(int $id);
}
