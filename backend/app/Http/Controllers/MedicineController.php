<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Medicine\MedicineService;
use App\Http\Requests\Medicine\StoreMedicineRequest;
use App\Http\Requests\Medicine\UpdateMedicineRequest;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function __construct(private MedicineService $medicineService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $medicines = $this->medicineService->getMedicines($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Medicines retrieved successfully.',
            'data' => $medicines,
        ], 200);
    }

    public function store(StoreMedicineRequest $request)
    {
        if (!$request->user()?->can('medicine.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $medicine = $this->medicineService->createMedicine($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Medicine created successfully.',
            'data' => $medicine,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $medicine = $this->medicineService->getMedicine($id);

        return response()->json([
            'success' => true,
            'message' => 'Medicine retrieved successfully.',
            'data' => $medicine,
        ], 200);
    }

    public function update(UpdateMedicineRequest $request, $id)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $medicine = $this->medicineService->updateMedicine($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Medicine updated successfully.',
            'data' => $medicine,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['status' => 'required|boolean']);
        
        $medicine = $this->medicineService->updateStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Medicine status updated successfully.',
            'data' => $medicine,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('medicine.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->medicineService->deleteMedicine($id);

        return response()->json([
            'success' => true,
            'message' => 'Medicine deleted successfully.',
        ], 200);
    }
}
