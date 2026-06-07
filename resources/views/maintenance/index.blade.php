<x-app-layout title="Maintenance Records">
    <div class="space-y-6" x-data="{ showModal: false }">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-cyan-600 dark:text-cyan-400">Fleet Operations</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Maintenance Records</h1>
            </div>
            @if(auth()->user()->canEdit())
                <button type="button" @click="showModal = true" class="btn-primary">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Record
                </button>
            @endif
        </div>

        <div class="card">
            @forelse($records as $record)
                @if($loop->first)
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Machine</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Performed</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Next Due</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @endif
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ $record->machine?->machine_code }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $record->machine?->machine_name }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap capitalize text-gray-700 dark:text-gray-300">{{ $record->maintenance_type }}</td>
                                    <td class="px-6 py-4"><p class="text-gray-900 dark:text-gray-100 truncate max-w-xs">{{ $record->description }}</p></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">{{ $record->performed_at->format('M j, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($record->next_maintenance_date)
                                            <span class="{{ $record->isOverdue() ? 'text-red-600 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                                                {{ $record->next_maintenance_date->format('M j, Y') }}
                                                @if($record->isOverdue()) (Overdue) @endif
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                @if($loop->last)
                            </tbody>
                        </table>
                    </div>
                @endif
            @empty
                <div class="text-center py-12 text-gray-500 dark:text-gray-400">No maintenance records found</div>
            @endforelse

            @if($records->hasPages())
                @include('components.pagination', ['paginator' => $records])
            @endif
        </div>

        {{-- Create Modal --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="showModal = false">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Add Maintenance Record</h3>
                </div>
                <form method="POST" action="{{ route('maintenance.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="label">Machine *</label>
                        <select name="machine_id" class="input @error('machine_id') input-error @enderror" required>
                            <option value="">Select machine</option>
                            @foreach($machines as $machine)
                                <option value="{{ $machine->id }}" @selected(old('machine_id') == $machine->id)>{{ $machine->machine_code }} - {{ $machine->machine_name }}</option>
                            @endforeach
                        </select>
                        @error('machine_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Type *</label>
                            <select name="maintenance_type" class="input @error('maintenance_type') input-error @enderror" required>
                                <option value="preventive" @selected(old('maintenance_type') === 'preventive')>Preventive</option>
                                <option value="corrective" @selected(old('maintenance_type') === 'corrective')>Corrective</option>
                                <option value="inspection" @selected(old('maintenance_type') === 'inspection')>Inspection</option>
                            </select>
                            @error('maintenance_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="label">Performed At *</label>
                            <input type="date" name="performed_at" value="{{ old('performed_at', now()->format('Y-m-d')) }}" class="input @error('performed_at') input-error @enderror" required>
                            @error('performed_at')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="label">Description *</label>
                        <textarea name="description" rows="3" class="input @error('description') input-error @enderror" required>{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Performed By</label>
                            <input type="text" name="performed_by" value="{{ old('performed_by') }}" class="input">
                        </div>
                        <div>
                            <label class="label">Next Maintenance Date</label>
                            <input type="date" name="next_maintenance_date" value="{{ old('next_maintenance_date') }}" class="input">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Cost</label>
                            <input type="number" step="0.01" min="0" name="cost" value="{{ old('cost') }}" class="input">
                        </div>
                        <div>
                            <label class="label">Remarks</label>
                            <textarea name="remarks" rows="2" class="input">{{ old('remarks') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="showModal = false" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Create Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
