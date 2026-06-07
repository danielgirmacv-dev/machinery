<?php

namespace App\Services;

use App\Models\Machine;
use App\Models\MovementHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MachineService
{
    /**
     * Create a new machine.
     */
    public function create(array $data, User $user): Machine
    {
        $data['created_by'] = $user->id;
        $data['updated_by'] = $user->id;

        return Machine::create($data);
    }

    /**
     * Update an existing machine.
     * Automatically tracks department/location changes in movement history.
     */
    public function update(Machine $machine, array $data, User $user): Machine
    {
        return DB::transaction(function () use ($machine, $data, $user) {
            // Check for department or location changes
            $departmentChanged = isset($data['department_id']) && $data['department_id'] != $machine->department_id;
            $locationChanged = isset($data['location_id']) && $data['location_id'] != $machine->location_id;

            // Track movement if department or location changed
            if ($departmentChanged || $locationChanged) {
                MovementHistory::create([
                    'machine_id' => $machine->id,
                    'from_department_id' => $machine->department_id,
                    'to_department_id' => $data['department_id'] ?? $machine->department_id,
                    'from_location_id' => $machine->location_id,
                    'to_location_id' => $data['location_id'] ?? $machine->location_id,
                    'moved_at' => now(),
                    'reason' => $data['movement_reason'] ?? null,
                    'created_by' => $user->id,
                    'created_at' => now(),
                ]);
            }

            // Remove movement_reason from data as it's not a machine field
            unset($data['movement_reason']);

            // Update the machine
            $data['updated_by'] = $user->id;
            $machine->update($data);

            return $machine->fresh();
        });
    }

    /**
     * Get dashboard statistics.
     */
    public function getStatistics(): array
    {
        $total = Machine::count();
        $working = Machine::status('working')->count();
        $faulty = Machine::status('faulty')->count();
        $disposed = Machine::status('disposed')->count();
        $underMaintenance = Machine::status('under_maintenance')->count();

        return [
            'total' => $total,
            'working' => $working,
            'faulty' => $faulty,
            'disposed' => $disposed,
            'under_maintenance' => $underMaintenance,
            'working_percentage' => $total > 0 ? round(($working / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Get machines by department.
     */
    public function getByDepartment(): array
    {
        return Machine::selectRaw('department_id, COUNT(*) as count')
            ->with('department')
            ->groupBy('department_id')
            ->get()
            ->map(function ($item) {
                return [
                    'department' => $item->department?->name ?? 'Unassigned',
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }

    /**
     * Get machines by category.
     */
    public function getByCategory(): array
    {
        return Machine::selectRaw('category_id, COUNT(*) as count')
            ->with('category')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'category' => $item->category?->name ?? 'Uncategorized',
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }
}
