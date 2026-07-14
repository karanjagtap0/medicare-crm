<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Category\CategoryService;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('category.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $categories = $this->categoryService->getCategories($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Categories retrieved successfully.',
            'data' => $categories,
        ], 200);
    }

    public function store(StoreCategoryRequest $request)
    {
        if (!$request->user()?->can('category.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $category = $this->categoryService->createCategory($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('category.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $category = $this->categoryService->getCategory($id);

        return response()->json([
            'success' => true,
            'message' => 'Category retrieved successfully.',
            'data' => $category,
        ], 200);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        if (!$request->user()?->can('category.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $category = $this->categoryService->updateCategory($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully.',
            'data' => $category,
        ], 200);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!$request->user()?->can('category.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate(['status' => 'required|boolean']);
        
        $category = $this->categoryService->updateStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Category status updated successfully.',
            'data' => $category,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('category.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->categoryService->deleteCategory($id);

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ], 200);
    }
}
