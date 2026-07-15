<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerService;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct(private CustomerService $customerService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('customer.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $customers = $this->customerService->getCustomers($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customers retrieved successfully.',
            'data' => $customers,
        ], 200);
    }

    public function store(StoreCustomerRequest $request)
    {
        if (!$request->user()?->can('customer.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $customer = $this->customerService->createCustomer($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully.',
            'data' => $customer,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('customer.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $customer = $this->customerService->getCustomer($id);

        return response()->json([
            'success' => true,
            'message' => 'Customer retrieved successfully.',
            'data' => $customer,
        ], 200);
    }

    public function update(UpdateCustomerRequest $request, $id)
    {
        if (!$request->user()?->can('customer.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $customer = $this->customerService->updateCustomer($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully.',
            'data' => $customer,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!$request->user()?->can('customer.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['status' => 'required|boolean']);
        
        $customer = $this->customerService->updateStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Customer status updated successfully.',
            'data' => $customer,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('customer.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->customerService->deleteCustomer($id);

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully.',
        ], 200);
    }
}
