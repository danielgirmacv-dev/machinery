<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MachineResource;
use App\Http\Resources\MaintenanceRecordResource;
use App\Models\Machine;
use App\Models\MaintenanceRecord;
use App\Services\MachineService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(
        private MachineService $machineService
    ) {}

    /**
     * Get dashboard data.
     */
    public function index(): JsonResponse
    {
        // Get statistics
        $statistics = $this->machineService->getStatistics();

        // Get recent machines (last 5 created/updated)
        $recentMachines = Machine::with(['category', 'department', 'location'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Get upcoming maintenance (next 30 days)
        $upcomingMaintenance = MaintenanceRecord::with('machine')
            ->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '>=', now())
            ->where('next_maintenance_date', '<=', now()->addDays(30))
            ->orderBy('next_maintenance_date')
            ->limit(5)
            ->get();

        // Get overdue maintenance
        $overdueMaintenance = MaintenanceRecord::with('machine')
            ->whereNotNull('next_maintenance_date')
            ->where('next_maintenance_date', '<', now())
            ->orderBy('next_maintenance_date')
            ->limit(5)
            ->get();

        // Get machines by department
        $byDepartment = $this->machineService->getByDepartment();

        // Get machines by category
        $byCategory = $this->machineService->getByCategory();

        return response()->json([
            'data' => [
                'statistics' => $statistics,
                'recent_machines' => MachineResource::collection($recentMachines),
                'upcoming_maintenance' => MaintenanceRecordResource::collection($upcomingMaintenance),
                'overdue_maintenance' => MaintenanceRecordResource::collection($overdueMaintenance),
                'by_department' => $byDepartment,
                'by_category' => $byCategory,
            ],
        ]);
    }
}
