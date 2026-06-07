<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($request->user()->id === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->update(['is_active' => false]);
        $user->tokens()->delete();

        return redirect()->route('users.index')->with('success', 'User deactivated successfully.');
    }
}
