<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Tax\TaxService;
use App\Http\Requests\Tax\StoreTaxRequest;
use App\Http\Requests\Tax\UpdateTaxRequest;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function __construct(private TaxService $taxService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('tax.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $taxes = $this->taxService->getTaxes($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Taxes retrieved successfully.',
            'data' => $taxes,
        ], 200);
    }

    public function store(StoreTaxRequest $request)
    {
        if (!$request->user()?->can('tax.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $tax = $this->taxService->createTax($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tax created successfully.',
            'data' => $tax,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('tax.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $tax = $this->taxService->getTax($id);

        return response()->json([
            'success' => true,
            'message' => 'Tax retrieved successfully.',
            'data' => $tax,
        ], 200);
    }

    public function update(UpdateTaxRequest $request, $id)
    {
        if (!$request->user()?->can('tax.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $tax = $this->taxService->updateTax($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tax updated successfully.',
            'data' => $tax,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!$request->user()?->can('tax.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['status' => 'required|boolean']);
        
        $tax = $this->taxService->updateStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Tax status updated successfully.',
            'data' => $tax,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('tax.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->taxService->deleteTax($id);

        return response()->json([
            'success' => true,
            'message' => 'Tax deleted successfully.',
        ], 200);
    }
}
