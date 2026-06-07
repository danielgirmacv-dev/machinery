<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Machine;
use App\Models\MaintenanceRecord;
use App\Services\MachineService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private MachineService $machineService
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $showMaintenanceRadar = !in_array($user->role, ['it', 'viewer'], true);

        $statistics = $this->machineService->getStatistics();

        $recentMachines = Machine::with(['category', 'machineType', 'department', 'location'])
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        $upcomingMaintenance = collect();
        $overdueMaintenance = collect();

        if ($showMaintenanceRadar) {
            $upcomingMaintenance = MaintenanceRecord::with('machine')
                ->whereNotNull('next_maintenance_date')
                ->where('next_maintenance_date', '>=', now())
                ->where('next_maintenance_date', '<=', now()->addDays(30))
                ->orderBy('next_maintenance_date')
                ->limit(5)
                ->get();

            $overdueMaintenance = MaintenanceRecord::with('machine')
                ->whereNotNull('next_maintenance_date')
                ->where('next_maintenance_date', '<', now())
                ->orderBy('next_maintenance_date')
                ->limit(5)
                ->get();
        }

        return view('dashboard.index', compact(
            'statistics',
            'recentMachines',
            'upcomingMaintenance',
            'overdueMaintenance',
            'showMaintenanceRadar'
        ));
    }
}
