<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Medicine\MedicineImageService;
use App\Http\Requests\Medicine\StoreMedicineImageRequest;
use App\Http\Requests\Medicine\ReplaceMedicineImageRequest;
use App\Http\Requests\Medicine\ReorderMedicineImageRequest;
use Illuminate\Http\Request;

class MedicineImageController extends Controller
{
    public function __construct(private MedicineImageService $medicineImageService)
    {
    }

    public function index(Request $request, $medicine)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $images = $this->medicineImageService->getMedicineImages((int) $medicine);

        return response()->json([
            'success' => true,
            'message' => 'Images retrieved successfully.',
            'data' => $images,
        ], 200);
    }

    public function store(StoreMedicineImageRequest $request, $medicine)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $images = $this->medicineImageService->uploadImages((int) $medicine, $request->file('images'));

        return response()->json([
            'success' => true,
            'message' => 'Images uploaded successfully.',
            'data' => $images,
        ], 201);
    }

    public function show(Request $request, $image)
    {
        if (!$request->user()?->can('medicine.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $imageRecord = $this->medicineImageService->getImage((int) $image);

        return response()->json([
            'success' => true,
            'message' => 'Image details retrieved.',
            'data' => $imageRecord,
        ], 200);
    }

    public function update(ReplaceMedicineImageRequest $request, $image)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $imageRecord = $this->medicineImageService->replaceImage((int) $image, $request->file('image'));

        return response()->json([
            'success' => true,
            'message' => 'Image replaced successfully.',
            'data' => $imageRecord,
        ], 200);
    }

    public function setPrimary(Request $request, $image)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $imageRecord = $this->medicineImageService->setPrimary((int) $image);

        return response()->json([
            'success' => true,
            'message' => 'Primary image updated.',
            'data' => $imageRecord,
        ], 200);
    }

    public function reorder(ReorderMedicineImageRequest $request)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->medicineImageService->reorder($request->validated('orders'));

        return response()->json([
            'success' => true,
            'message' => 'Images reordered successfully.',
        ], 200);
    }

    public function destroy(Request $request, $image)
    {
        if (!$request->user()?->can('medicine.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->medicineImageService->deleteImage((int) $image);

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.',
        ], 200);
    }
}
