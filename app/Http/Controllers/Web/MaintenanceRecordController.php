<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenanceRecord\StoreMaintenanceRecordRequest;
use App\Models\Machine;
use App\Models\MaintenanceRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceRecordController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $records = MaintenanceRecord::with(['machine', 'createdBy'])
            ->orderByDesc('performed_at')
            ->paginate(15)
            ->withQueryString();

        $machines = Machine::orderBy('machine_code')->get(['id', 'machine_code', 'machine_name']);

        return view('maintenance.index', compact('records', 'machines'));
    }

    public function store(StoreMaintenanceRecordRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()->canEdit(), 403);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        MaintenanceRecord::create($data);

        return redirect()->route('maintenance.index')->with('success', 'Maintenance record created.');
    }
}
