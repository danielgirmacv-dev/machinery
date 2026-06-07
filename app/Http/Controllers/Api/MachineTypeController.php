<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MachineTypeResource;
use App\Models\MachineType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MachineTypeController extends Controller
{
    /**
     * Display a listing of machine types.
     */
    public function index(Request $request)
    {
        $query = MachineType::withCount('machines')->with('category');

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('category_code', 'like', '%' . $request->search . '%')
                  ->orWhere('eec_number', 'like', '%' . $request->search . '%');
            });
        }

        $query->orderBy('category_id')->orderBy('category_code');

        if ($request->boolean('all')) {
            return MachineTypeResource::collection($query->get());
        }

        $perPage = min($request->get('per_page', 30), 200);
        return MachineTypeResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created machine type.
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->user()->canEdit()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'category_id'   => ['required', 'exists:categories,id'],
            'category_code' => ['required', 'string', 'max:20'],
            'description'   => ['required', 'string', 'max:200'],
            'eec_number'    => ['required', 'string', 'max:30'],
        ]);

        $machineType = MachineType::create($validated);

        return response()->json([
            'message' => 'Machine type created successfully.',
            'data'    => new MachineTypeResource($machineType),
        ], 201);
    }

    /**
     * Display the specified machine type.
     */
    public function show(MachineType $machineType): MachineTypeResource
    {
        return new MachineTypeResource($machineType->loadCount('machines')->load('category'));
    }

    /**
     * Update the specified machine type.
     */
    public function update(Request $request, MachineType $machineType): JsonResponse
    {
        if (!$request->user()->canEdit()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'category_id'   => ['sometimes', 'exists:categories,id'],
            'category_code' => ['sometimes', 'string', 'max:20'],
            'description'   => ['sometimes', 'string', 'max:200'],
            'eec_number'    => ['sometimes', 'string', 'max:30'],
        ]);

        $machineType->update($validated);

        return response()->json([
            'message' => 'Machine type updated successfully.',
            'data'    => new MachineTypeResource($machineType),
        ]);
    }

    /**
     * Remove the specified machine type.
     */
    public function destroy(Request $request, MachineType $machineType): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Only administrators can delete machine types.'], 403);
        }

        if ($machineType->machines()->exists()) {
            return response()->json(['message' => 'Cannot delete machine type. It has associated machines.'], 422);
        }

        $machineType->delete();

        return response()->json(['message' => 'Machine type deleted successfully.']);
    }
}
