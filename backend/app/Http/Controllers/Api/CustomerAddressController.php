<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerAddressService;
use App\Http\Requests\StoreCustomerAddressRequest;
use App\Http\Requests\UpdateCustomerAddressRequest;
use Illuminate\Http\Request;

class CustomerAddressController extends Controller
{
    public function __construct(private CustomerAddressService $addressService)
    {
    }

    public function index(Request $request, $id)
    {
        if (!$request->user()?->can('customer.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $addresses = $this->addressService->getCustomerAddresses($id);

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

        $address = $this->addressService->createCustomerAddress($id, $request->validated());

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

        $address = $this->addressService->updateCustomerAddress($id, $request->validated());

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

        $this->addressService->deleteCustomerAddress($id);

        return response()->json([
            'success' => true,
            'message' => 'Customer address deleted successfully.',
        ], 200);
    }
}
