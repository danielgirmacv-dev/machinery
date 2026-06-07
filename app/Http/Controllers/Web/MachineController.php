<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Api\MachineController as ApiMachineController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Machine\StoreMachineRequest;
use App\Http\Requests\Machine\UpdateMachineRequest;
use App\Models\Category;
use App\Models\Department;
use App\Models\Location;
use App\Models\Machine;
use App\Models\MachineType;
use App\Services\MachineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MachineController extends Controller
{
    public function __construct(
        private MachineService $machineService,
        private ApiMachineController $apiMachineController
    ) {}

    public function index(Request $request): View
    {
        $query = Machine::with(['category', 'machineType', 'department', 'location']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('status')) {
            $query->status($request->status);
        }
        if ($request->filled('category_id')) {
            $query->category($request->category_id);
        }
        if ($request->filled('department_id')) {
            $query->department($request->department_id);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $allowedSortFields = ['machine_code', 'machine_name', 'status', 'purchase_date', 'created_at', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields, true)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $machines = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $viewMode = $request->get('view', 'table');

        return view('machines.index', compact('machines', 'categories', 'departments', 'viewMode'));
    }

    public function show(Request $request, Machine $machine): View
    {
        $machine->load(['category', 'machineType', 'department', 'location', 'createdBy', 'updatedBy']);
        $tab = $request->get('tab', 'details');

        $maintenanceRecords = collect();
        $movementHistories = collect();

        if ($tab === 'maintenance') {
            $maintenanceRecords = $machine->maintenanceRecords()
                ->with('createdBy')
                ->orderByDesc('performed_at')
                ->paginate(10)
                ->withQueryString();
        }

        if ($tab === 'movement') {
            $movementHistories = $machine->movementHistories()
                ->with(['fromDepartment', 'toDepartment', 'fromLocation', 'toLocation', 'createdBy'])
                ->orderByDesc('moved_at')
                ->paginate(10)
                ->withQueryString();
        }

        return view('machines.show', compact('machine', 'tab', 'maintenanceRecords', 'movementHistories'));
    }

    public function createCategory(): View
    {
        abort_unless(auth()->user()->canEdit(), 403);

        $categories = Category::orderBy('name')->get();

        return view('machines.create.category', compact('categories'));
    }

    public function createType(Category $category): View
    {
        abort_unless(auth()->user()->canEdit(), 403);

        $machineTypes = MachineType::where('category_id', $category->id)
            ->orderBy('category_code')
            ->get();

        return view('machines.create.type', compact('category', 'machineTypes'));
    }

    public function createForm(Category $category, MachineType $machineType): View
    {
        abort_unless(auth()->user()->canEdit(), 403);
        abort_unless($machineType->category_id === $category->id, 404);

        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('machines.create.form', compact('category', 'machineType', 'departments', 'locations'));
    }

    public function store(StoreMachineRequest $request): RedirectResponse
    {
        $this->machineService->create($request->validated(), $request->user());

        return redirect()->route('machines.index')->with('success', 'Equipment added to the fleet successfully!');
    }

    public function edit(Machine $machine): View
    {
        abort_unless(auth()->user()->canEdit(), 403);

        $machine->load(['category', 'machineType', 'department', 'location']);
        $categories = Category::orderBy('name')->get();
        $machineTypes = MachineType::where('category_id', $machine->category_id)->orderBy('category_code')->get();
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();

        return view('machines.edit', compact('machine', 'categories', 'machineTypes', 'departments', 'locations'));
    }

    public function update(UpdateMachineRequest $request, Machine $machine): RedirectResponse
    {
        $this->machineService->update($machine, $request->validated(), $request->user());

        return redirect()->route('machines.show', $machine)->with('success', 'Machine updated successfully.');
    }

    public function destroy(Machine $machine): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $code = $machine->machine_code;
        $machine->delete();

        return redirect()->route('machines.index')->with('success', "Machine {$code} deleted successfully.");
    }

    public function template(Request $request)
    {
        return $this->apiMachineController->template($request);
    }

    public function import(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canEdit(), 403);

        $response = $this->apiMachineController->import($request);
        $payload = json_decode($response->getContent(), true);

        if ($response->getStatusCode() >= 400) {
            return back()->with('error', $payload['message'] ?? 'Import failed.');
        }

        $data = $payload['data'] ?? [];
        $message = "Imported {$data['imported']} machine(s).";
        if (($data['skipped'] ?? 0) > 0) {
            $message .= " Skipped {$data['skipped']} invalid rows.";
        }

        return redirect()->route('machines.index')->with('success', $message);
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $response = $this->apiMachineController->bulkDelete($request);
        $payload = json_decode($response->getContent(), true);

        if ($response->getStatusCode() >= 400) {
            return back()->with('error', $payload['message'] ?? 'Bulk delete failed.');
        }

        return redirect()->route('machines.index')->with('success', $payload['message'] ?? 'Machines deleted.');
    }

    public function deleteAll(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $response = $this->apiMachineController->deleteAll($request);
        $payload = json_decode($response->getContent(), true);

        if ($response->getStatusCode() >= 400) {
            return back()->with('error', $payload['message'] ?? 'Delete all failed.');
        }

        return redirect()->route('machines.index')->with('success', $payload['message'] ?? 'Inventory cleared.');
    }
}
