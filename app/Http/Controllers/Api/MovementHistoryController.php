<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovementHistoryResource;
use App\Models\MovementHistory;
use Illuminate\Http\Request;

class MovementHistoryController extends Controller
{
    /**
     * Display a listing of movement histories.
     */
    public function index(Request $request)
    {
        $query = MovementHistory::with([
            'machine',
            'fromDepartment',
            'toDepartment',
            'fromLocation',
            'toLocation',
            'createdBy',
        ]);

        // Filter by machine
        if ($request->filled('machine_id')) {
            $query->where('machine_id', $request->machine_id);
        }

        // Filter by department
        if ($request->filled('department_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('from_department_id', $request->department_id)
                  ->orWhere('to_department_id', $request->department_id);
            });
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('moved_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('moved_at', '<=', $request->to_date);
        }

        // Default ordering
        $query->orderBy('moved_at', 'desc');

        $perPage = min($request->get('per_page', 15), 100);

        return MovementHistoryResource::collection($query->paginate($perPage));
    }

    /**
     * Display the specified movement history.
     */
    public function show(MovementHistory $movementHistory): MovementHistoryResource
    {
        return new MovementHistoryResource(
            $movementHistory->load([
                'machine',
                'fromDepartment',
                'toDepartment',
                'fromLocation',
                'toLocation',
                'createdBy',
            ])
        );
    }
}
