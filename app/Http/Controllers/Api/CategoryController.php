<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('machines');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $query->orderBy('name');

        // Return all or paginated based on request
        if ($request->boolean('all')) {
            return CategoryResource::collection($query->get());
        }

        $perPage = min($request->get('per_page', 15), 100);
        return CategoryResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category->loadCount('machines'));
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return response()->json([
            'message' => 'Category updated successfully.',
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Request $request, Category $category): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Only administrators can delete categories.',
            ], 403);
        }

        // Check if category has machines
        if ($category->machines()->exists()) {
            return response()->json([
                'message' => 'Cannot delete category. It has associated machines.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully.',
        ]);
    }
}
