<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Supplier\SupplierService;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(private SupplierService $supplierService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('supplier.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $suppliers = $this->supplierService->getSuppliers($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Suppliers retrieved successfully.',
            'data' => $suppliers,
        ], 200);
    }

    public function store(StoreSupplierRequest $request)
    {
        if (!$request->user()?->can('supplier.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $supplier = $this->supplierService->createSupplier($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully.',
            'data' => $supplier,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('supplier.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $supplier = $this->supplierService->getSupplier($id);

        return response()->json([
            'success' => true,
            'message' => 'Supplier retrieved successfully.',
            'data' => $supplier,
        ], 200);
    }

    public function update(UpdateSupplierRequest $request, $id)
    {
        if (!$request->user()?->can('supplier.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $supplier = $this->supplierService->updateSupplier($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully.',
            'data' => $supplier,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!$request->user()?->can('supplier.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['status' => 'required|boolean']);
        
        $supplier = $this->supplierService->updateStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Supplier status updated successfully.',
            'data' => $supplier,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('supplier.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->supplierService->deleteSupplier($id);

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully.',
        ], 200);
    }
}
