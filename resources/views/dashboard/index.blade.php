<x-app-layout title="Dashboard">
    @php
        $total = $statistics['total'] ?? 0;
        $workingPct = $statistics['working_percentage'] ?? 0;

        $statCards = [
            [
                'title' => 'Total Fleet',
                'value' => $total,
                'link' => route('machines.index'),
                'color' => 'cyan',
                'gradient' => 'from-cyan-500 to-blue-600',
                'bg' => 'bg-cyan-500/10 dark:bg-cyan-500/[0.08]',
                'text' => 'text-cyan-600 dark:text-cyan-400',
                'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h8.25A2.25 2.25 0 0 1 16.5 6.75V9h1.97c.597 0 1.17.237 1.592.659l1.28 1.28c.422.422.658.995.658 1.592v3.219A2.25 2.25 0 0 1 19.75 18H18a2.25 2.25 0 1 0-4.5 0h-3a2.25 2.25 0 1 0-4.5 0H6a2.25 2.25 0 0 1-2.25-2.25v-9Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12h5.25M8.25 18h.008v.008H8.25V18Zm7.5 0h.008v.008h-.008V18Z"/></svg>',
            ],
            [
                'title' => 'Operational',
                'value' => $statistics['working'] ?? 0,
                'link' => route('machines.index', ['status' => 'working']),
                'color' => 'emerald',
                'gradient' => 'from-emerald-500 to-green-600',
                'bg' => 'bg-emerald-500/10 dark:bg-emerald-500/[0.08]',
                'text' => 'text-emerald-600 dark:text-emerald-400',
                'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
            ],
            [
                'title' => 'Critical',
                'value' => $statistics['faulty'] ?? 0,
                'link' => route('machines.index', ['status' => 'faulty']),
                'color' => 'red',
                'gradient' => 'from-red-500 to-rose-600',
                'bg' => 'bg-red-500/10 dark:bg-red-500/[0.08]',
                'text' => 'text-red-600 dark:text-red-400',
                'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m10.34 3.94-7.02 12.15c-.78 1.35.19 3.04 1.74 3.04h14.04c1.55 0 2.52-1.69 1.74-3.04L13.82 3.94a2 2 0 0 0-3.48 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.75v4.25m0 2.75h.008v.008H12v-.008Z"/></svg>',
            ],
            [
                'title' => 'In Service',
                'value' => $statistics['under_maintenance'] ?? 0,
                'link' => route('machines.index', ['status' => 'under_maintenance']),
                'color' => 'amber',
                'gradient' => 'from-amber-500 to-orange-600',
                'bg' => 'bg-amber-500/10 dark:bg-amber-500/[0.08]',
                'text' => 'text-amber-600 dark:text-amber-400',
                'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63"/></svg>',
            ],
            [
                'title' => 'Retired',
                'value' => $statistics['disposed'] ?? 0,
                'link' => route('machines.index', ['status' => 'disposed']),
                'color' => 'gray',
                'gradient' => 'from-gray-500 to-slate-600',
                'bg' => 'bg-gray-500/10 dark:bg-gray-500/[0.08]',
                'text' => 'text-gray-500 dark:text-gray-400',
                'icon' => '<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v10.5a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V6.75a1.5 1.5 0 0 1 1.5-1.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5"/></svg>',
            ],
        ];
    @endphp

    <div class="space-y-8">
        {{-- ═══════════════════════════════════════ --}}
        {{-- HERO BANNER --}}
        {{-- ═══════════════════════════════════════ --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#0c1929] via-[#0f2136] to-[#0a1628] text-white shadow-2xl animate-in slide-in-from-bottom">
            {{-- Ambient glow orbs --}}
            <div class="absolute -top-20 -left-20 w-72 h-72 bg-eec-cyan/20 rounded-full blur-[100px] animate-glow"></div>
            <div class="absolute -bottom-16 -right-16 w-60 h-60 bg-blue-500/15 rounded-full blur-[80px] animate-glow delay-300"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-eec-teal/5 rounded-full blur-[120px]"></div>

            {{-- Grid pattern overlay --}}
            <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.1) 1px, transparent 1px); background-size: 40px 40px;"></div>

            <div class="relative px-8 py-10 md:px-10 md:py-12 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                            <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                            <span class="text-[11px] font-bold uppercase tracking-widest text-emerald-300">System Online</span>
                        </div>
                        <span class="text-[11px] font-medium text-white/30">{{ now()->format('M j, Y · H:i') }}</span>
                    </div>
                    <div>
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-none">Fleet Dashboard</h1>
                        <p class="text-base text-white/40 font-medium mt-2">Real-time machinery health & inventory overview</p>
                    </div>
                </div>

                {{-- Hero KPI cards --}}
                <div class="flex flex-col sm:flex-row gap-4 lg:min-w-[340px]">
                    <div class="flex-1 rounded-2xl bg-white/[0.06] backdrop-blur-sm border border-white/[0.08] px-6 py-5 hover:bg-white/[0.08] transition-colors group">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-white/35 mb-1.5">Fleet Size</p>
                        <div class="flex items-end gap-2">
                            <p class="text-4xl font-black tabular-nums" x-data="{ val: 0 }" x-init="let target = {{ $total }}; let step = Math.ceil(target / 30); let iv = setInterval(() => { val += step; if (val >= target) { val = target; clearInterval(iv); } }, 30)" x-text="val">0</p>
                            <span class="text-[10px] font-bold text-white/25 mb-1.5">units</span>
                        </div>
                    </div>
                    <div class="flex-1 rounded-2xl bg-white/[0.06] backdrop-blur-sm border border-white/[0.08] px-6 py-5 hover:bg-white/[0.08] transition-colors group">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-white/35 mb-1.5">Uptime Rate</p>
                        <div class="flex items-end gap-1">
                            <p class="text-4xl font-black text-emerald-400 tabular-nums" x-data="{ val: 0 }" x-init="let target = {{ $workingPct }}; let iv = setInterval(() => { val++; if (val >= target) { val = target; clearInterval(iv); } }, 20)" x-text="val">0</p>
                            <span class="text-lg font-bold text-emerald-400/60 mb-0.5">%</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress bar --}}
            <div class="relative px-8 pb-6 md:px-10">
                <div class="flex items-center justify-between text-[10px] font-bold text-white/25 uppercase tracking-wider mb-2">
                    <span>Fleet Health</span>
                    <span>{{ $workingPct }}% operational</span>
                </div>
                <div class="h-1.5 rounded-full bg-white/[0.06] overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 via-cyan-400 to-blue-400 transition-all duration-1000 ease-out"
                         x-data="{ w: 0 }" x-init="setTimeout(() => w = {{ min($workingPct, 100) }}, 300)"
                         :style="'width:' + w + '%'"></div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════ --}}
        {{-- STAT CARDS --}}
        {{-- ═══════════════════════════════════════ --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-5">
            @foreach($statCards as $i => $card)
                <a href="{{ $card['link'] }}"
                   class="group relative card card-hover p-6 rounded-2xl flex flex-col animate-in slide-in-from-bottom delay-{{ ($i + 1) * 100 }}"
                   style="animation-delay: {{ ($i + 1) * 80 }}ms">
                    {{-- Icon --}}
                    <div class="inline-flex p-3 rounded-xl {{ $card['bg'] }} {{ $card['text'] }} mb-4 group-hover:scale-110 transition-transform duration-300">
                        {!! $card['icon'] !!}
                    </div>
                    {{-- Label --}}
                    <p class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">{{ $card['title'] }}</p>
                    {{-- Value --}}
                    <p class="text-3xl xl:text-4xl font-extrabold text-gray-900 dark:text-white leading-none tabular-nums">{{ $card['value'] }}</p>
                    {{-- Hover CTA --}}
                    <p class="mt-auto pt-4 text-[10px] font-bold {{ $card['text'] }} uppercase tracking-wider opacity-0 group-hover:opacity-100 translate-y-1 group-hover:translate-y-0 transition-all duration-300">View details →</p>
                </a>
            @endforeach
        </div>

        {{-- ═══════════════════════════════════════ --}}
        {{-- LOWER PANELS --}}
        {{-- ═══════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Recent inventory --}}
            <div class="{{ $showMaintenanceRadar ? 'lg:col-span-12 xl:col-span-7' : 'lg:col-span-12' }}">
                <div class="card overflow-hidden rounded-2xl animate-in slide-in-from-bottom" style="animation-delay: 500ms">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-white/[0.06] flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-cyan-500/10 dark:bg-cyan-500/[0.08] flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                                <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Recent Additions</h2>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">Latest registered equipment</p>
                            </div>
                        </div>
                        <a href="{{ route('machines.index') }}" class="text-[10px] font-bold text-eec-cyan uppercase tracking-wider hover:underline underline-offset-4 transition-colors">View All →</a>
                    </div>
                    <div class="divide-y divide-gray-50 dark:divide-white/[0.04]">
                        @forelse($recentMachines as $machine)
                            <a href="{{ route('machines.show', $machine) }}" class="flex items-center justify-between gap-4 px-6 py-4 hover:bg-gray-50/80 dark:hover:bg-white/[0.02] transition-colors group">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-white/[0.04] flex items-center justify-center text-gray-400 dark:text-gray-500 group-hover:bg-eec-cyan group-hover:text-white transition-all duration-300 shrink-0">
                                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $machine->machine_code }}</p>
                                        <p class="text-[11px] font-medium text-gray-400 dark:text-gray-500 truncate">{{ $machine->machine_name }}</p>
                                    </div>
                                </div>
                                <x-status-badge :status="$machine->status" />
                            </a>
                        @empty
                            <div class="px-6 py-16 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-white/[0.04] flex items-center justify-center mx-auto mb-3 text-gray-300 dark:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 11.625l2.25-2.25M12 11.625l-2.25 2.25"/></svg>
                                </div>
                                <p class="text-sm text-gray-400 dark:text-gray-500">No assets registered yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            @if($showMaintenanceRadar)
                <div class="lg:col-span-12 xl:col-span-5 space-y-5">
                    {{-- Maintenance Radar --}}
                    <div class="relative overflow-hidden rounded-2xl bg-[#0c1929] dark:bg-[#080e1a] text-white shadow-2xl shadow-black/20 animate-in slide-in-from-bottom" style="animation-delay: 600ms">
                        {{-- Ambient glow --}}
                        <div class="absolute top-0 right-0 w-40 h-40 bg-eec-cyan/10 rounded-full blur-[60px]"></div>

                        <div class="relative px-6 py-5 border-b border-white/[0.06] flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-eec-cyan/10 flex items-center justify-center text-eec-cyan">
                                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-sm font-bold">Maintenance Radar</h2>
                                    <p class="text-[10px] text-white/30 font-medium">Upcoming service events</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/15 border border-emerald-500/20">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-400">Live</span>
                            </div>
                        </div>
                        <div class="p-4 space-y-2.5">
                            @forelse($upcomingMaintenance as $record)
                                <div class="flex items-start gap-3 bg-white/[0.03] hover:bg-white/[0.06] p-4 rounded-xl border border-white/[0.04] transition-colors">
                                    <div class="w-8 h-8 rounded-lg bg-white/[0.06] flex items-center justify-center text-eec-cyan shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $record->machine?->machine_code }}</p>
                                        <p class="text-[11px] font-medium text-eec-cyan/80 mt-0.5">
                                            @if($record->next_maintenance_date)
                                                {{ $record->next_maintenance_date->format('M j') }}
                                            @endif
                                            — {{ $record->maintenance_type }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <div class="w-11 h-11 rounded-xl bg-white/[0.04] flex items-center justify-center mx-auto mb-3 text-white/20">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                    </div>
                                    <p class="text-white/25 text-sm font-medium">All clear — no upcoming events</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="p-4 pt-0">
                            <a href="{{ route('maintenance.index') }}" class="block w-full text-center py-3.5 bg-white/[0.04] hover:bg-white/[0.08] rounded-xl text-[10px] font-bold uppercase tracking-widest text-white/50 hover:text-white/80 transition-all duration-300 border border-white/[0.04]">Full Schedule →</a>
                        </div>
                    </div>

                    @if($overdueMaintenance->isNotEmpty())
                        {{-- Overdue alert --}}
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-red-600 to-rose-600 text-white shadow-xl shadow-red-500/20 animate-in slide-in-from-bottom" style="animation-delay: 700ms">
                            <div class="absolute inset-0 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2032%2032%22%20width%3D%2232%22%20height%3D%2232%22%20fill%3D%22none%22%20stroke%3D%22rgb(255%20255%20255%20%2F%200.08)%22%3E%3Cpath%20d%3D%22M0%2032L32%200%22%2F%3E%3C%2Fsvg%3E')] opacity-50"></div>
                            <div class="relative p-5 flex items-center gap-4">
                                <div class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center shrink-0 animate-pulse">
                                    <svg class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                </div>
                                <div>
                                    <p class="text-base font-extrabold">{{ $overdueMaintenance->count() }} Overdue</p>
                                    <p class="text-[11px] font-medium text-white/70">Service events require immediate attention</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
