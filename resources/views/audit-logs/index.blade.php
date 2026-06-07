<x-app-layout title="Audit Log">
    <div class="space-y-6" x-data="{ deleteId: null, deleteAllOpen: false }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-cyan-600 dark:text-cyan-400">System Trace</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Audit Log</h1>
            </div>
            @if(auth()->user()->isAdmin() && $auditLogs->isNotEmpty())
                <button type="button" @click="deleteAllOpen = true" class="flex items-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/20 rounded-xl transition-all font-bold text-sm shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                    Clear All Logs
                </button>
            @endif
        </div>

        {{-- Filters --}}
        <div class="card p-4">
            <form method="GET" action="{{ route('audit-logs.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <select name="action" onchange="this.form.submit()" class="input">
                    <option value="">All Actions</option>
                    <option value="create" @selected(request('action') === 'create')>Create</option>
                    <option value="update" @selected(request('action') === 'update')>Update</option>
                    <option value="delete" @selected(request('action') === 'delete')>Delete</option>
                </select>
                <select name="model" onchange="this.form.submit()" class="input">
                    <option value="">All Models</option>
                    <option value="Machine" @selected(request('model') === 'Machine')>Machine</option>
                    <option value="Category" @selected(request('model') === 'Category')>Category</option>
                    <option value="Department" @selected(request('model') === 'Department')>Department</option>
                    <option value="Location" @selected(request('model') === 'Location')>Location</option>
                    <option value="MaintenanceRecord" @selected(request('model') === 'MaintenanceRecord')>Maintenance Record</option>
                </select>
                @if(request()->hasAny(['action', 'model']))
                    <a href="{{ route('audit-logs.index') }}" class="btn-secondary text-center">Reset Filters</a>
                @endif
            </form>
        </div>

        <div class="card">
            @forelse($auditLogs as $log)
                @if($loop->first)
                    @php
                        $actionColors = [
                            'create' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                            'update' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                            'delete' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                        ];
                    @endphp
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Timestamp</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Action</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Model</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Changes</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @endif
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $log->created_at->format('M j, Y g:i A') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $log->user?->name ?? 'System' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full capitalize {{ $actionColors[$log->action] ?? 'bg-gray-100 text-gray-800' }}">{{ $log->action }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $log->model_name }} #{{ $log->auditable_id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs">
                                        @if($log->action === 'create')
                                            <span class="text-green-600 dark:text-green-400">New record created</span>
                                        @elseif($log->action === 'delete')
                                            <span class="text-red-600 dark:text-red-400">Record deleted</span>
                                        @elseif($log->action === 'update')
                                            @php $changes = $log->changes; @endphp
                                            <div class="space-y-1">
                                                @foreach(array_slice($changes, 0, 3, true) as $key => $value)
                                                    <div class="text-xs">
                                                        <span class="font-medium">{{ $key }}:</span>
                                                        <span class="text-red-500 line-through">{{ is_array($value) ? ($value['old'] ?? 'null') : $value }}</span>
                                                        →
                                                        <span class="text-green-600">{{ is_array($value) ? ($value['new'] ?? 'null') : $value }}</span>
                                                    </div>
                                                @endforeach
                                                @if(count($changes) > 3)
                                                    <span class="text-xs text-gray-400">+{{ count($changes) - 3 }} more changes</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        @if(auth()->user()->isAdmin())
                                            <button type="button" @click="deleteId = {{ $log->id }}" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors p-2">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                @if($loop->last)
                            </tbody>
                        </table>
                    </div>
                @endif
            @empty
                <div class="text-center py-12 text-gray-500 dark:text-gray-400">No audit logs found</div>
            @endforelse

            @if($auditLogs->hasPages())
                @include('components.pagination', ['paginator' => $auditLogs])
            @endif
        </div>

        {{-- Delete Single --}}
        <div x-show="deleteId !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="deleteId = null"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-8">
                <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Delete Audit Log</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete this audit log entry?</p>
                <form method="POST" x-bind:action="'{{ url('/audit-logs') }}/' + deleteId" class="flex justify-end gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteId = null" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-danger">Delete</button>
                </form>
            </div>
        </div>

        {{-- Delete All --}}
        <div x-show="deleteAllOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="deleteAllOpen = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-8 border border-red-200 dark:border-red-800">
                <h3 class="text-lg font-black text-red-600 mb-2">Clear All Audit Logs</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">This will permanently delete all audit log entries. This action cannot be undone.</p>
                <form method="POST" action="{{ route('audit-logs.delete-all') }}" class="flex justify-end gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteAllOpen = false" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-danger">Clear All</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
