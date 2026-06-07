<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MachineType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MachineTypeController extends Controller
{
    public function index(Request $request): View
    {
        $query = MachineType::withCount('machines')->with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('category_code', 'like', "%{$search}%")
                    ->orWhere('eec_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('category', fn ($q) => $q->where('name', 'like', $request->category . '%'));
        }

        $machineTypes = $query->orderBy('category_id')->orderBy('category_code')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('settings.categories', compact('machineTypes', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()->canEdit(), 403);

        $validated = $request->validate([
            'parent_category' => ['required', 'string', 'max:50'],
            'machine_group' => ['required', 'string', 'max:200'],
            'category_id_code' => ['required', 'string', 'max:20'],
            'description' => ['required', 'string', 'max:200'],
            'eec_config' => ['required', 'string', 'max:30'],
        ]);

        $fullName = "{$validated['parent_category']} | {$validated['machine_group']}";
        $category = Category::firstOrCreate(
            ['name' => $fullName],
            ['description' => "Group for {$validated['machine_group']}"]
        );

        MachineType::create([
            'category_id' => $category->id,
            'category_code' => $validated['category_id_code'],
            'description' => $validated['description'],
            'eec_number' => $validated['eec_config'],
        ]);

        return redirect()->route('settings.categories')->with('success', 'Classification deployed successfully.');
    }

    public function update(Request $request, MachineType $machineType): RedirectResponse
    {
        abort_unless(auth()->user()->canEdit(), 403);

        $validated = $request->validate([
            'parent_category' => ['required', 'string', 'max:50'],
            'machine_group' => ['required', 'string', 'max:200'],
            'category_id_code' => ['required', 'string', 'max:20'],
            'description' => ['required', 'string', 'max:200'],
            'eec_config' => ['required', 'string', 'max:30'],
        ]);

        $fullName = "{$validated['parent_category']} | {$validated['machine_group']}";
        $category = Category::firstOrCreate(
            ['name' => $fullName],
            ['description' => "Group for {$validated['machine_group']}"]
        );

        $machineType->update([
            'category_id' => $category->id,
            'category_code' => $validated['category_id_code'],
            'description' => $validated['description'],
            'eec_number' => $validated['eec_config'],
        ]);

        return redirect()->route('settings.categories')->with('success', 'Classification updated.');
    }

    public function destroy(MachineType $machineType): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($machineType->machines()->exists()) {
            return back()->with('error', 'Cannot delete machine type. It has associated machines.');
        }

        $machineType->delete();

        return redirect()->route('settings.categories')->with('success', 'Classification removed.');
    }
}
