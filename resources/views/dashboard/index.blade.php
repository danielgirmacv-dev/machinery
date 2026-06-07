<x-app-layout title="Dashboard">
    @php
        $statCards = [
            [
                'title' => 'Total Inventory', 'value' => $statistics['total'] ?? 0,
                'link' => route('machines.index'),
                'bg' => 'bg-gradient-to-br from-cyan-500 to-blue-600',
                'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>',
            ],
            [
                'title' => 'Operational', 'value' => $statistics['working'] ?? 0,
                'link' => route('machines.index', ['status' => 'working']),
                'bg' => 'bg-gradient-to-br from-green-500 to-emerald-600',
                'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            ],
            [
                'title' => 'Critical Fault', 'value' => $statistics['faulty'] ?? 0,
                'link' => route('machines.index', ['status' => 'faulty']),
                'bg' => 'bg-gradient-to-br from-red-500 to-rose-600',
                'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>',
            ],
            [
                'title' => 'Active Service', 'value' => $statistics['under_maintenance'] ?? 0,
                'link' => route('machines.index', ['status' => 'under_maintenance']),
                'bg' => 'bg-gradient-to-br from-orange-500 to-amber-600',
                'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.1 5.1a2.121 2.121 0 11-3-3l5.1-5.1m0 0L15.41 5.18A2.121 2.121 0 0118.4 2.18l1.42 1.42a2.121 2.121 0 01-.01 3l-7.01 7.01m0 0l-3.39 1.56 1.56-3.39"/></svg>',
            ],
            [
                'title' => 'Decommissioned', 'value' => $statistics['disposed'] ?? 0,
                'link' => route('machines.index', ['status' => 'disposed']),
                'bg' => 'bg-gradient-to-br from-gray-500 to-slate-600',
                'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 11.625l2.25-2.25M12 11.625l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>',
            ],
        ];
    @endphp

    <div class="space-y-10 animate-in slide-in-from-bottom duration-700">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-black uppercase tracking-[0.4em] text-cyan-600 dark:text-cyan-400">Operational Overview</span>
                    <div class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></div>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-9 h-9 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605"/></svg>
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">Fleet Dashboard</h1>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5">
            @foreach($statCards as $card)
                <a href="{{ $card['link'] }}" class="group relative bg-white dark:bg-gray-800 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all">
                    <div class="inline-flex p-3 rounded-2xl {{ $card['bg'] }} text-white mb-6 shadow-xl group-hover:scale-110 transition-transform duration-300">
                        {!! $card['icon'] !!}
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">{{ $card['title'] }}</p>
                    <p class="text-3xl font-black text-gray-900 dark:text-white leading-none">{{ $card['value'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="{{ $showMaintenanceRadar ? 'lg:col-span-12 xl:col-span-7' : 'lg:col-span-12' }} space-y-8">
                <div class="card border-transparent shadow-2xl overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between">
                        <h2 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">Recent Inventory Additions</h2>
                        <a href="{{ route('machines.index') }}" class="text-[10px] font-black text-cyan-600 dark:text-cyan-400 uppercase tracking-widest hover:underline">Full Library →</a>
                    </div>
                    <div class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($recentMachines as $machine)
                            <a href="{{ route('machines.show', $machine) }}" class="flex items-center justify-between px-8 py-5 hover:bg-cyan-50/30 dark:hover:bg-cyan-900/10 transition-colors">
                                <div>
                                    <p class="text-sm font-black text-gray-900 dark:text-gray-100">{{ $machine->machine_code }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $machine->machine_name }}</p>
                                </div>
                                <x-status-badge :status="$machine->status" />
                            </a>
                        @empty
                            <div class="px-8 py-12 text-center text-gray-400 italic">No assets registered in the current cycle.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            @if($showMaintenanceRadar)
                <div class="lg:col-span-12 xl:col-span-5 space-y-8">
                    <div class="card bg-gray-900 dark:bg-black text-white shadow-2xl overflow-hidden">
                        <div class="px-8 py-6 border-b border-white/10 flex items-center justify-between">
                            <h2 class="text-lg font-black uppercase tracking-tight">Maintenance Radar</h2>
                            <div class="px-2 py-1 rounded bg-cyan-500 text-[8px] font-black uppercase">Live</div>
                        </div>
                        <div class="p-4 space-y-3">
                            @forelse($upcomingMaintenance as $record)
                                <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
                                    <p class="text-sm font-black text-white">{{ $record->machine?->machine_code }}</p>
                                    <p class="text-xs font-black text-cyan-400 uppercase">
                                        @if($record->next_maintenance_date)
                                            {{ $record->next_maintenance_date->format('M j') }}
                                        @endif
                                        — {{ $record->maintenance_type }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-white/30 italic text-sm text-center py-8">Radar clear. No upcoming service events.</p>
                            @endforelse
                        </div>
                        <div class="p-8 pt-4">
                            <a href="{{ route('maintenance.index') }}" class="block w-full text-center py-4 bg-white/10 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white/20">Full Schedule Explorer</a>
                        </div>
                    </div>

                    @if($overdueMaintenance->isNotEmpty())
                        <div class="card bg-red-600 text-white shadow-2xl shadow-red-500/30">
                            <div class="p-6 flex items-center gap-4">
                                <div>
                                    <p class="text-lg font-black">System Critical</p>
                                    <p class="text-[10px] font-bold text-white/60 uppercase">{{ $overdueMaintenance->count() }} Overdue Service Events</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
