<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(Request $request)
    {
        $query = Department::withCount('machines');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $query->orderBy('name');

        // Return all or paginated based on request
        if ($request->boolean('all')) {
            return DepartmentResource::collection($query->get());
        }

        $perPage = min($request->get('per_page', 15), 100);
        return DepartmentResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created department.
     */
    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = Department::create($request->validated());

        return response()->json([
            'message' => 'Department created successfully.',
            'data' => new DepartmentResource($department),
        ], 201);
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department): DepartmentResource
    {
        return new DepartmentResource($department->loadCount('machines'));
    }

    /**
     * Update the specified department.
     */
    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse
    {
        $department->update($request->validated());

        return response()->json([
            'message' => 'Department updated successfully.',
            'data' => new DepartmentResource($department),
        ]);
    }

    /**
     * Remove the specified department.
     */
    public function destroy(Request $request, Department $department): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Only administrators can delete departments.',
            ], 403);
        }

        // Check if department has machines
        if ($department->machines()->exists()) {
            return response()->json([
                'message' => 'Cannot delete department. It has associated machines.',
            ], 422);
        }

        $department->delete();

        return response()->json([
            'message' => 'Department deleted successfully.',
        ]);
    }
}
