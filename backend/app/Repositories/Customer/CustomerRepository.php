<?php

namespace App\Repositories\Customer;

use App\Interfaces\Customer\CustomerRepositoryInterface;
use App\Models\Customer;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function getCustomers(array $filters)
    {
        $perPage = $filters['per_page'] ?? 10;
        
        return Customer::query()
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function createCustomer(array $data)
    {
        return Customer::create($data);
    }

    public function getCustomer(int $id)
    {
        return Customer::findOrFail($id);
    }

    public function updateCustomer(int $id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    public function deleteCustomer(int $id)
    {
        $customer = Customer::findOrFail($id);
        return $customer->delete();
    }
}
