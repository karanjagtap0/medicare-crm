<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Uom\UomService;
use App\Http\Requests\Uom\StoreUomRequest;
use App\Http\Requests\Uom\UpdateUomRequest;
use Illuminate\Http\Request;

class UomController extends Controller
{
    public function __construct(private UomService $uomService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('uom.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $uoms = $this->uomService->getUoms($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Units of Measure retrieved successfully.',
            'data' => $uoms,
        ], 200);
    }

    public function store(StoreUomRequest $request)
    {
        if (!$request->user()?->can('uom.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $uom = $this->uomService->createUom($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Unit of Measure created successfully.',
            'data' => $uom,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('uom.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $uom = $this->uomService->getUom($id);

        return response()->json([
            'success' => true,
            'message' => 'Unit of Measure retrieved successfully.',
            'data' => $uom,
        ], 200);
    }

    public function update(UpdateUomRequest $request, $id)
    {
        if (!$request->user()?->can('uom.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $uom = $this->uomService->updateUom($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Unit of Measure updated successfully.',
            'data' => $uom,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!$request->user()?->can('uom.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['status' => 'required|boolean']);
        
        $uom = $this->uomService->updateStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Unit of Measure status updated successfully.',
            'data' => $uom,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('uom.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->uomService->deleteUom($id);

        return response()->json([
            'success' => true,
            'message' => 'Unit of Measure deleted successfully.',
        ], 200);
    }
}
