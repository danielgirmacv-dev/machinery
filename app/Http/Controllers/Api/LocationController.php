<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of locations.
     */
    public function index(Request $request)
    {
        $query = Location::withCount('machines');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('building', 'like', '%' . $request->search . '%')
                  ->orWhere('floor', 'like', '%' . $request->search . '%');
            });
        }

        $query->orderBy('name');

        // Return all or paginated based on request
        if ($request->boolean('all')) {
            return LocationResource::collection($query->get());
        }

        $perPage = min($request->get('per_page', 15), 100);
        return LocationResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created location.
     */
    public function store(StoreLocationRequest $request): JsonResponse
    {
        $location = Location::create($request->validated());

        return response()->json([
            'message' => 'Location created successfully.',
            'data' => new LocationResource($location),
        ], 201);
    }

    /**
     * Display the specified location.
     */
    public function show(Location $location): LocationResource
    {
        return new LocationResource($location->loadCount('machines'));
    }

    /**
     * Update the specified location.
     */
    public function update(UpdateLocationRequest $request, Location $location): JsonResponse
    {
        $location->update($request->validated());

        return response()->json([
            'message' => 'Location updated successfully.',
            'data' => new LocationResource($location),
        ]);
    }

    /**
     * Remove the specified location.
     */
    public function destroy(Request $request, Location $location): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized. Only administrators can delete locations.',
            ], 403);
        }

        // Check if location has machines
        if ($location->machines()->exists()) {
            return response()->json([
                'message' => 'Cannot delete location. It has associated machines.',
            ], 422);
        }

        $location->delete();

        return response()->json([
            'message' => 'Location deleted successfully.',
        ]);
    }
}
