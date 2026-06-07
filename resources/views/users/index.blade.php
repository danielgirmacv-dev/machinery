<x-app-layout title="User Management">
    <div class="space-y-6" x-data="{
        showModal: false,
        editId: null,
        form: { name: '', email: '', password: '', role: 'viewer', is_active: true },
        openCreate() {
            this.editId = null;
            this.form = { name: '', email: '', password: '', role: 'viewer', is_active: true };
            this.showModal = true;
        },
        openEdit(id, name, email, role, isActive) {
            this.editId = id;
            this.form = { name, email, password: '', role, is_active: isActive };
            this.showModal = true;
        }
    }" x-init="@if($errors->any() && old('_user_form')) showModal = true; editId = {{ old('_user_id') ?: 'null' }}; @endif">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-cyan-600 dark:text-cyan-400">Administration</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h1>
            </div>
            @if(auth()->user()->isAdmin())
                <button type="button" @click="openCreate()" class="btn-primary">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add User
                </button>
            @endif
        </div>

        <form method="GET" action="{{ route('users.index') }}" class="card p-4">
            <div class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="input flex-1">
                <button type="submit" class="btn-secondary">Search</button>
                @if(request('search'))
                    <a href="{{ route('users.index') }}" class="btn-secondary">Clear</a>
                @endif
            </div>
        </form>

        <div class="card">
            @forelse($users as $user)
                @if($loop->first)
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @endif
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                        'it' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                        'viewer' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full capitalize {{ $roleColors[$user->role] ?? $roleColors['viewer'] }}">{{ $user->role }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if(auth()->user()->isAdmin())
                                            <button type="button" @click="openEdit({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role }}', {{ $user->is_active ? 'true' : 'false' }})" class="text-primary-600 hover:text-primary-700 dark:text-cyan-400">
                                                <svg class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                            </button>
                                            @if(auth()->id() !== $user->id && $user->is_active)
                                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline ml-2" onsubmit="return confirm('Deactivate this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-600" title="Deactivate">
                                                        <svg class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                @if($loop->last)
                            </tbody>
                        </table>
                    </div>
                @endif
            @empty
                <div class="text-center py-12 text-gray-500 dark:text-gray-400">No users found</div>
            @endforelse

            @if($users->hasPages())
                @include('components.pagination', ['paginator' => $users])
            @endif
        </div>

        {{-- Create/Edit Modal --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="editId ? 'Edit User' : 'Add User'"></h3>
                </div>
                <form method="POST" x-bind:action="editId ? '{{ url('/users') }}/' + editId : '{{ route('users.store') }}'" class="p-6 space-y-4">
                    @csrf
                    <template x-if="editId"><input type="hidden" name="_method" value="PUT"></template>
                    <input type="hidden" name="_user_form" value="1">
                    <input type="hidden" name="_user_id" x-bind:value="editId">

                    <div>
                        <label class="label">Name *</label>
                        <input type="text" name="name" x-model="form.name" value="{{ old('name') }}" required class="input @error('name') input-error @enderror">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Email *</label>
                        <input type="email" name="email" x-model="form.email" value="{{ old('email') }}" required class="input @error('email') input-error @enderror">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label" x-text="editId ? 'Password (leave blank to keep current)' : 'Password *'"></label>
                        <input type="password" name="password" x-model="form.password" :required="!editId" class="input @error('password') input-error @enderror">
                        @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="label">Role *</label>
                        <select name="role" x-model="form.role" class="input @error('role') input-error @enderror" required>
                            <option value="viewer">Viewer</option>
                            <option value="it">IT</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" x-model="form.is_active" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</label>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="showModal = false" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary" x-text="editId ? 'Save Changes' : 'Create User'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
