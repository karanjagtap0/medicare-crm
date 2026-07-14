<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Medicine\MedicineBatchService;
use App\Http\Requests\Medicine\StoreMedicineBatchRequest;
use App\Http\Requests\Medicine\UpdateMedicineBatchRequest;
use Illuminate\Http\Request;

class MedicineBatchController extends Controller
{
    public function __construct(private MedicineBatchService $medicineBatchService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $batches = $this->medicineBatchService->getMedicineBatches($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Medicine Batches retrieved successfully.',
            'data' => $batches,
        ], 200);
    }

    public function store(StoreMedicineBatchRequest $request)
    {
        if (!$request->user()?->can('medicine.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $batch = $this->medicineBatchService->createMedicineBatch($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Medicine Batch created successfully.',
            'data' => $batch,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $batch = $this->medicineBatchService->getMedicineBatch($id);

        return response()->json([
            'success' => true,
            'message' => 'Medicine Batch retrieved successfully.',
            'data' => $batch,
        ], 200);
    }

    public function update(UpdateMedicineBatchRequest $request, $id)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $batch = $this->medicineBatchService->updateMedicineBatch($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Medicine Batch updated successfully.',
            'data' => $batch,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('medicine.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->medicineBatchService->deleteMedicineBatch($id);

        return response()->json([
            'success' => true,
            'message' => 'Medicine Batch deleted successfully.',
        ], 200);
    }
}
