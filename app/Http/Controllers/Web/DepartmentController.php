<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::withCount('machines')->orderBy('name')->paginate(15);

        return view('settings.departments', compact('departments'));
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()->canEdit(), 403);

        Department::create($request->validated());

        return redirect()->route('settings.departments')->with('success', 'Department created successfully.');
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        abort_unless(auth()->user()->canEdit(), 403);

        $department->update($request->validated());

        return redirect()->route('settings.departments')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($department->machines()->exists()) {
            return back()->with('error', 'Cannot delete department. It has associated machines.');
        }

        $department->delete();

        return redirect()->route('settings.departments')->with('success', 'Department deleted successfully.');
    }
}
