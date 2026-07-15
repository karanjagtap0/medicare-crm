<?php

namespace App\Repositories\Customer;

use App\Interfaces\Customer\CustomerAddressRepositoryInterface;
use App\Models\Customer;
use App\Models\CustomerAddress;

class CustomerAddressRepository implements CustomerAddressRepositoryInterface
{
    public function getCustomerAddresses(int $customerId)
    {
        $customer = Customer::findOrFail($customerId);
        return $customer->addresses()->get();
    }

    public function createCustomerAddress(int $customerId, array $data)
    {
        $customer = Customer::findOrFail($customerId);
        
        if (isset($data['is_default']) && $data['is_default']) {
            $customer->addresses()->update(['is_default' => false]);
        }

        return $customer->addresses()->create($data);
    }

    public function updateCustomerAddress(int $id, array $data)
    {
        $address = CustomerAddress::findOrFail($id);
        
        if (isset($data['is_default']) && $data['is_default']) {
            CustomerAddress::where('customer_id', $address->customer_id)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($data);
        return $address;
    }

    public function deleteCustomerAddress(int $id)
    {
        $address = CustomerAddress::findOrFail($id);
        return $address->delete();
    }
}
