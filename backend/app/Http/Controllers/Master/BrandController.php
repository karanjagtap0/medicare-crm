<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Brand\BrandService;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct(private BrandService $brandService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('brand.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $brands = $this->brandService->getBrands($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Brands retrieved successfully.',
            'data' => $brands,
        ], 200);
    }

    public function store(StoreBrandRequest $request)
    {
        if (!$request->user()?->can('brand.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $brand = $this->brandService->createBrand($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully.',
            'data' => $brand,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('brand.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $brand = $this->brandService->getBrand($id);

        return response()->json([
            'success' => true,
            'message' => 'Brand retrieved successfully.',
            'data' => $brand,
        ], 200);
    }

    public function update(UpdateBrandRequest $request, $id)
    {
        if (!$request->user()?->can('brand.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $brand = $this->brandService->updateBrand($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Brand updated successfully.',
            'data' => $brand,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!$request->user()?->can('brand.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['status' => 'required|boolean']);
        
        $brand = $this->brandService->updateStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Brand status updated successfully.',
            'data' => $brand,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('brand.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->brandService->deleteBrand($id);

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully.',
        ], 200);
    }
}
