<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()?->can('customer.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $customers = Customer::query();

        return response()->json([
            'success' => true,
            'message' => 'Customers retrieved successfully.',
            'data' => $customers->paginate($request->per_page ?? 10),
        ], 200);
    }

    public function store(StoreCustomerRequest $request)
    {
        if (!$request->user()?->can('customer.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $data = $request->validated();
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $customer = Customer::create($data);

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

        $customer = Customer::findOrFail($id);

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

        $customer = Customer::findOrFail($id);
        
        $data = $request->validated();
        $data['updated_by'] = Auth::id();
        
        $customer->update($data);

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
        
        $customer = Customer::findOrFail($id);
        $customer->update([
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

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

        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully.',
        ], 200);
    }
}
