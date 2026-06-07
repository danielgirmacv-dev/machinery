<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::withCount('machines')->orderBy('name')->paginate(15);

        return view('settings.locations', compact('locations'));
    }

    public function store(StoreLocationRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()->canEdit(), 403);

        Location::create($request->validated());

        return redirect()->route('settings.locations')->with('success', 'Location registered successfully.');
    }

    public function update(UpdateLocationRequest $request, Location $location): RedirectResponse
    {
        abort_unless(auth()->user()->canEdit(), 403);

        $location->update($request->validated());

        return redirect()->route('settings.locations')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($location->machines()->exists()) {
            return back()->with('error', 'Cannot delete location. It has associated machines.');
        }

        $location->delete();

        return redirect()->route('settings.locations')->with('success', 'Location decommissioned successfully.');
    }
}
