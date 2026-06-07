<x-app-layout title="{{ $machine->machine_code }}">
    <div class="space-y-8 animate-in slide-in-from-bottom duration-500" x-data="{ deleteOpen: false }">
        {{-- Header Profile --}}
        <div class="relative overflow-hidden bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10">
                <div class="flex items-start sm:items-center gap-6">
                    <div class="p-4 rounded-[1.5rem] bg-gradient-to-br from-cyan-600 to-blue-700 text-white shadow-xl shadow-cyan-500/20">
                        <svg class="w-12 h-12 animate-spin-slow" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0M9 12a3 3 0 1 1 6 0 3 3 0 0 1-6 0Z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-cyan-600 dark:text-cyan-400">Inventory Profile</span>
                            <div class="w-1.5 h-1.5 rounded-full bg-cyan-500"></div>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $machine->machineType?->category_code ?? 'UNCLASSIFIED' }}</span>
                        </div>
                        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight leading-none mb-2">{{ $machine->machine_code }}</h1>
                        <p class="text-gray-500 dark:text-gray-400 font-bold flex items-center gap-2 flex-wrap">
                            <span class="text-gray-900 dark:text-gray-200">{{ $machine->machineType?->description ?? $machine->machine_name }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                            <span class="text-xs italic uppercase tracking-widest text-gray-400">{{ $machine->model ?? 'Standard Edition' }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="flex flex-col items-center sm:items-end mr-4">
                        <x-status-badge :status="$machine->status" />
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-2 px-1">Active Status</p>
                    </div>

                    @if(auth()->user()->canEdit())
                        <div class="flex gap-2 bg-gray-50 dark:bg-gray-900/50 p-2 rounded-[1.5rem]">
                            <a href="{{ route('machines.edit', $machine) }}" class="p-3 bg-white dark:bg-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-all shadow-sm group">
                                <svg class="h-5 w-5 text-gray-500 group-hover:text-cyan-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                            </a>
                            @if(auth()->user()->isAdmin())
                                <button type="button" @click="deleteOpen = true" class="p-3 bg-red-500 rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-red-500/20">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex items-center gap-4 bg-gray-100/50 dark:bg-gray-800/50 p-1.5 rounded-2xl w-fit border border-gray-200 dark:border-gray-700 flex-wrap">
            @foreach(['details' => 'Asset Profile', 'maintenance' => 'Maintenance Log', 'movement' => 'Operational Trace'] as $key => $label)
                <a href="{{ route('machines.show', [$machine, 'tab' => $key]) }}"
                   class="py-3 px-6 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all flex items-center gap-2 {{ $tab === $key ? 'bg-white dark:bg-gray-700 shadow-xl text-cyan-600 dark:text-cyan-400 border border-gray-100 dark:border-gray-600' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        @if($tab === 'details')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 space-y-8">
                    <div class="card p-8 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-800/50 border-transparent shadow-xl">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-8">Classification</h3>
                        <dl class="space-y-6">
                            <div>
                                <dt class="text-[10px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-widest mb-1">Category ID</dt>
                                <dd class="text-lg font-black text-gray-700 dark:text-gray-200">{{ $machine->machine_type ?? $machine->machineType?->category_code ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-widest mb-1">Section</dt>
                                <dd class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ trim(explode('|', $machine->category?->name ?? '')[1] ?? '-') ?: '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-widest mb-1">EEC Compliance</dt>
                                <dd><span class="px-2 py-0.5 rounded bg-green-100 dark:bg-green-900/30 text-[10px] font-black text-green-700 dark:text-green-400 uppercase">{{ $machine->machineType?->eec_number ?? 'PENDING' }}</span></dd>
                            </div>
                        </dl>
                    </div>

                    <div class="card p-8 bg-indigo-600 dark:bg-indigo-900 text-white shadow-xl">
                        <h3 class="text-xl font-bold mb-8">Deployment</h3>
                        <dl class="space-y-6">
                            <div>
                                <dt class="text-[10px] font-black text-indigo-200/60 uppercase tracking-widest mb-1">Primary Department</dt>
                                <dd class="text-lg font-bold">{{ $machine->department?->name ?? 'Central Unit' }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-black text-indigo-200/60 uppercase tracking-widest mb-1">Assigned Location</dt>
                                <dd class="text-sm font-medium">{{ $machine->location?->full_name ?? 'Unassigned' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="lg:col-span-2 space-y-8">
                    <div class="card p-8 shadow-2xl bg-white/50 dark:bg-gray-800/50 backdrop-blur-md">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-8">Technical Specifications</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            <div class="space-y-6">
                                <div><dt class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Chassis / Serial</dt><dd class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $machine->serial_number ?? 'NOT SPECIFIED' }}</dd></div>
                                <div><dt class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Power Output</dt><dd class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $machine->power ?? '-' }} <span class="text-[9px] text-gray-400">Kw/HP</span></dd></div>
                                <div><dt class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Weight</dt><dd class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $machine->weight ?? '-' }} <span class="text-[9px] text-gray-400">KG</span></dd></div>
                                <div><dt class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Machine Group</dt><dd class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $machine->machine_group ?? '-' }}</dd></div>
                            </div>
                            <div class="space-y-6">
                                <div><dt class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Engine Type</dt><dd class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $machine->engine_type ?? '-' }}</dd></div>
                                <div><dt class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Engine Serial</dt><dd class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $machine->engine_serial_number ?? '-' }}</dd></div>
                                <div><dt class="text-[10px] font-black text-gray-300 uppercase tracking-widest mb-1">Plate No.</dt><dd class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $machine->plate_number ?? '-' }}</dd></div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/30 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <p class="text-[10px] font-black text-cyan-700 dark:text-cyan-400 uppercase tracking-tighter mb-1">Manufactured</p>
                                <p class="text-3xl font-black text-gray-900 dark:text-white leading-none mb-4">{{ $machine->manufacturing_year ?? 'N/A' }}</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Received Date</p>
                                <p class="text-xs font-black text-gray-600 dark:text-gray-400 mb-2">{{ $machine->received_date?->format('M j, Y') ?? 'NOT LOGGED' }}</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Manufacturer</p>
                                <p class="text-xs font-black text-gray-600 dark:text-gray-400 truncate">{{ $machine->manufacturer ?? 'General Market' }}</p>
                            </div>
                        </div>

                        <div class="mt-12 bg-gray-900 dark:bg-black p-6 rounded-[1.5rem] flex items-center justify-between">
                            <div>
                                <h4 class="text-[10px] font-black text-cyan-500 uppercase tracking-[0.3em]">Fleet Valuation</h4>
                                <p class="text-2xl font-black text-white leading-none mt-1"><span class="text-sm opacity-40 mr-1">$</span>{{ $machine->price ? number_format($machine->price) : 'N/A' }}</p>
                            </div>
                            <div class="text-right hidden sm:block">
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Acquisition & Supplier</p>
                                <p class="text-sm font-bold text-gray-300">{{ $machine->purchase_date?->format('M j, Y') ?? 'No Record' }}</p>
                                <p class="text-[10px] font-bold text-cyan-500 mt-1 truncate max-w-[150px]">{{ $machine->supplier ?? 'DIRECT PURCHASE' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
                        <div class="md:col-span-3 card p-8 shadow-xl">
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-500 mb-6">Asset Description</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed font-medium">{{ $machine->description ?? 'No system description provided.' }}</p>
                        </div>
                        <div class="md:col-span-2 card p-8 shadow-xl bg-orange-50 dark:bg-orange-900/10 border-orange-100 dark:border-orange-800/30">
                            <h3 class="text-xs font-black uppercase tracking-widest text-orange-500 mb-6">Service Notes</h3>
                            <p class="text-sm text-orange-800/70 dark:text-orange-300/60 leading-relaxed font-medium italic">{{ $machine->remarks ?? 'No critical operator warnings registered for this asset.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($tab === 'maintenance')
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="px-10 py-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Maintenance History</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Fleet Service Logs — Asset {{ $machine->machine_code }}</p>
                </div>
                @forelse($maintenanceRecords as $record)
                    <div class="px-10 py-6 hover:bg-cyan-50/50 dark:hover:bg-cyan-900/10 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0">
                        <div class="flex flex-col md:flex-row items-start justify-between gap-6">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-black text-cyan-600 dark:text-cyan-400 uppercase">Event Protocol</span>
                                    <span class="text-xs font-bold text-gray-900 dark:text-white capitalize">{{ $record->maintenance_type }} Maintenance</span>
                                </div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 max-w-xl">{{ $record->description }}</p>
                                <p class="text-[10px] font-black text-gray-500 uppercase mt-3">Verified by {{ $record->performed_by ?? 'Field Official' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-black text-gray-900 dark:text-white">{{ $record->performed_at->format('M j, Y') }}</p>
                                @if($record->next_maintenance_date)
                                    <div class="mt-3 inline-flex px-3 py-1 rounded-lg text-[10px] font-black uppercase {{ $record->isOverdue() ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                                        Next Due: {{ $record->next_maintenance_date->format('M j, Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-32 text-center">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">No Service Events</h3>
                        <p class="text-gray-500 text-sm">This machine has no recorded maintenance history.</p>
                    </div>
                @endforelse
                @if($maintenanceRecords instanceof \Illuminate\Pagination\LengthAwarePaginator && $maintenanceRecords->hasPages())
                    @include('components.pagination', ['paginator' => $maintenanceRecords])
                @endif
            </div>
        @elseif($tab === 'movement')
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="px-10 py-8 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Movement Trace</h2>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Lifecycle Tracking — Asset {{ $machine->machine_code }}</p>
                </div>
                @forelse($movementHistories as $movement)
                    <div class="px-10 py-8 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/5 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-0">
                        <div class="flex flex-col md:flex-row items-start justify-between gap-8">
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $movement->summary }}</h4>
                                <div class="bg-white/50 dark:bg-gray-900/30 p-4 rounded-2xl border border-gray-50 dark:border-gray-700">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Formal Reason</p>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 italic">"{{ $movement->reason ?? 'Operational re-assignment requested' }}"</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-gray-900 dark:text-white uppercase">{{ $movement->moved_at->format('M j, Y') }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">{{ $movement->moved_at->format('g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-32 text-center text-gray-500">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Stable Deployment</h3>
                        <p class="text-sm">No recorded movements for this asset.</p>
                    </div>
                @endforelse
                @if($movementHistories instanceof \Illuminate\Pagination\LengthAwarePaginator && $movementHistories->hasPages())
                    @include('components.pagination', ['paginator' => $movementHistories])
                @endif
            </div>
        @endif

        {{-- Delete Dialog --}}
        <div x-show="deleteOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="deleteOpen = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-8">
                <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Delete Machine Record</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Warning: You are about to permanently decommission "{{ $machine->machine_code }}" from the digital inventory.</p>
                <form method="POST" action="{{ route('machines.destroy', $machine) }}" class="flex justify-end gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteOpen = false" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-danger">Confirm Decommission</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
