<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenanceRecord\StoreMaintenanceRecordRequest;
use App\Http\Requests\MaintenanceRecord\UpdateMaintenanceRecordRequest;
use App\Http\Resources\MaintenanceRecordResource;
use App\Models\MaintenanceRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaintenanceRecordController extends Controller
{
    /**
     * Display a listing of maintenance records.
     */
    public function index(Request $request)
    {
        $query = MaintenanceRecord::with(['machine', 'createdBy']);

        // Filter by machine
        if ($request->filled('machine_id')) {
            $query->forMachine($request->machine_id);
        }

        // Filter by type
        if ($request->filled('maintenance_type')) {
            $query->type($request->maintenance_type);
        }

        // Filter upcoming/overdue
        if ($request->boolean('upcoming')) {
            $query->upcoming();
        } elseif ($request->boolean('overdue')) {
            $query->overdue();
        }

        // Default ordering
        $query->orderBy('performed_at', 'desc');

        $perPage = min($request->get('per_page', 15), 100);

        return MaintenanceRecordResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created maintenance record.
     */
    public function store(StoreMaintenanceRecordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $record = MaintenanceRecord::create($data);

        return response()->json([
            'message' => 'Maintenance record created successfully.',
            'data' => new MaintenanceRecordResource($record->load(['machine', 'createdBy'])),
        ], 201);
    }

    /**
     * Display the specified maintenance record.
     */
    public function show(MaintenanceRecord $maintenanceRecord): MaintenanceRecordResource
    {
        return new MaintenanceRecordResource(
            $maintenanceRecord->load(['machine', 'createdBy'])
        );
    }

    /**
     * Update the specified maintenance record.
     */
    public function update(UpdateMaintenanceRecordRequest $request, MaintenanceRecord $maintenanceRecord): JsonResponse
    {
        $maintenanceRecord->update($request->validated());

        return response()->json([
            'message' => 'Maintenance record updated successfully.',
            'data' => new MaintenanceRecordResource($maintenanceRecord->load(['machine', 'createdBy'])),
        ]);
    }

    /**
     * Remove the specified maintenance record.
     */
    public function destroy(Request $request, MaintenanceRecord $maintenanceRecord): JsonResponse
    {
        if (!$request->user()->canEdit()) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 403);
        }

        $maintenanceRecord->delete();

        return response()->json([
            'message' => 'Maintenance record deleted successfully.',
        ]);
    }

    /**
     * Get upcoming maintenance schedule.
     */
    public function upcoming(Request $request)
    {
        $days = $request->get('days', 30);

        $records = MaintenanceRecord::with(['machine'])
            ->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->where('next_maintenance_date', '<=', now()->addDays($days))
            ->orderBy('next_maintenance_date')
            ->get();

        return MaintenanceRecordResource::collection($records);
    }
}
