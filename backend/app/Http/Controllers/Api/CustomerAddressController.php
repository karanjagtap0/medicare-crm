<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Http\Requests\StoreCustomerAddressRequest;
use App\Http\Requests\UpdateCustomerAddressRequest;
use Illuminate\Http\Request;

class CustomerAddressController extends Controller
{
    public function index(Request $request, $id)
    {
        if (!$request->user()?->can('customer.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $customer = Customer::findOrFail($id);
        $addresses = $customer->addresses()->get();

        return response()->json([
            'success' => true,
            'message' => 'Customer addresses retrieved successfully.',
            'data' => $addresses,
        ], 200);
    }

    public function store(StoreCustomerAddressRequest $request, $id)
    {
        if (!$request->user()?->can('customer.edit') && !$request->user()?->can('customer.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $customer = Customer::findOrFail($id);
        
        $data = $request->validated();
        
        if (isset($data['is_default']) && $data['is_default']) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $address = $customer->addresses()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer address created successfully.',
            'data' => $address,
        ], 201);
    }

    public function update(UpdateCustomerAddressRequest $request, $id)
    {
        if (!$request->user()?->can('customer.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $address = CustomerAddress::findOrFail($id);
        
        $data = $request->validated();
        
        if (isset($data['is_default']) && $data['is_default']) {
            CustomerAddress::where('customer_id', $address->customer_id)
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer address updated successfully.',
            'data' => $address,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('customer.edit') && !$request->user()?->can('customer.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $address = CustomerAddress::findOrFail($id);
        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer address deleted successfully.',
        ], 200);
    }
}
