<?php

namespace App\Interfaces\Customer;

interface CustomerRepositoryInterface
{
    public function getCustomers(array $filters);
    public function createCustomer(array $data);
    public function getCustomer(int $id);
    public function updateCustomer(int $id, array $data);
    public function deleteCustomer(int $id);
}
