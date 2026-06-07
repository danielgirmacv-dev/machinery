<x-app-layout title="Machines">
    <div class="space-y-8 animate-in fade-in duration-500" x-data="{
        importOpen: false,
        deleteId: null,
        deleteAllOpen: false
    }">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-[10px] font-black uppercase tracking-[0.3em] text-cyan-600 dark:text-cyan-400">Inventory Management</span>
                    <div class="w-1.5 h-1.5 rounded-full bg-cyan-500"></div>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Machines List</h1>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex bg-gray-100 dark:bg-gray-800 p-1 rounded-xl border border-gray-200 dark:border-gray-700">
                    <a href="{{ route('machines.index', array_merge(request()->except('view'), ['view' => 'table'])) }}"
                       class="p-2 rounded-lg transition-all {{ $viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-cyan-600 dark:text-cyan-400' : 'text-gray-400 hover:text-gray-600' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75h-7.5a1.125 1.125 0 0 0-1.125 1.125v8.25c0 .621.504 1.125 1.125 1.125h7.5a1.125 1.125 0 0 0 1.125-1.125V5.625Z"/></svg>
                    </a>
                    <a href="{{ route('machines.index', array_merge(request()->except('view'), ['view' => 'grid'])) }}"
                       class="p-2 rounded-lg transition-all {{ $viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-cyan-600 dark:text-cyan-400' : 'text-gray-400 hover:text-gray-600' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z"/></svg>
                    </a>
                </div>

                @if(auth()->user()->canEdit())
                    <button type="button" @click="importOpen = true"
                            class="hidden sm:inline-flex items-center px-4 py-3 rounded-2xl border border-dashed border-cyan-500/40 bg-cyan-50/50 dark:bg-cyan-900/10 text-xs font-black tracking-widest text-cyan-700 dark:text-cyan-300 hover:bg-cyan-100/70 dark:hover:bg-cyan-900/30 transition-all">
                        <span class="w-2 h-2 rounded-full bg-cyan-500 mr-2 animate-pulse"></span>
                        IMPORT EXCEL / CSV
                    </button>
                    <a href="{{ route('machines.create') }}"
                       class="group flex items-center bg-gradient-to-r from-cyan-600 to-blue-700 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-xl shadow-cyan-500/20 hover:shadow-cyan-500/40 hover:scale-[1.02] active:scale-95 transition-all">
                        <svg class="h-5 w-5 mr-3 group-hover:rotate-90 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        New Machine
                    </a>
                @endif

                @if(auth()->user()->isAdmin())
                    <button type="button" @click="deleteAllOpen = true"
                            class="p-3 rounded-2xl bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/20 transition-all shadow-sm"
                            title="Delete All Machines">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                    </button>
                @endif
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl p-3 rounded-3xl shadow-xl shadow-gray-200/20 dark:shadow-none border border-gray-100 dark:border-gray-700">
            <form method="GET" action="{{ route('machines.index') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-center">
                @if(request('view'))
                    <input type="hidden" name="view" value="{{ request('view') }}">
                @endif
                <div class="lg:col-span-4 relative group">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400 group-focus-within:text-cyan-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by code, model, serial..."
                           class="w-full bg-gray-50/50 dark:bg-gray-900/50 border-transparent rounded-2xl py-3 pl-12 pr-4 text-sm focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500/30 transition-all font-medium">
                </div>

                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-3">
                    <select name="status" onchange="this.form.submit()"
                            class="w-full appearance-none bg-gray-50/50 dark:bg-gray-900/50 border-transparent rounded-2xl py-3 px-4 text-xs font-bold text-gray-600 dark:text-gray-300 focus:ring-2 focus:ring-cyan-500/20 cursor-pointer">
                        <option value="">Status: All</option>
                        <option value="working" @selected(request('status') === 'working')>Status: Working</option>
                        <option value="faulty" @selected(request('status') === 'faulty')>Status: Faulty</option>
                        <option value="under_maintenance" @selected(request('status') === 'under_maintenance')>Status: Maintenance</option>
                        <option value="disposed" @selected(request('status') === 'disposed')>Status: Disposed</option>
                    </select>

                    <select name="category_id" onchange="this.form.submit()"
                            class="w-full appearance-none bg-gray-50/50 dark:bg-gray-900/50 border-transparent rounded-2xl py-3 px-4 text-xs font-bold text-gray-600 dark:text-gray-300 focus:ring-2 focus:ring-cyan-500/20 cursor-pointer truncate pr-8">
                        <option value="">Category: All</option>
                        @foreach($categories as $cat)
                            @php $label = trim(explode('|', $cat->name)[1] ?? $cat->name); @endphp
                            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $label }}</option>
                        @endforeach
                    </select>

                    <select name="department_id" onchange="this.form.submit()"
                            class="w-full appearance-none bg-gray-50/50 dark:bg-gray-900/50 border-transparent rounded-2xl py-3 px-4 text-xs font-bold text-gray-600 dark:text-gray-300 focus:ring-2 focus:ring-cyan-500/20 cursor-pointer truncate pr-8 hidden md:block">
                        <option value="">Department: All</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
                        @endforeach
                    </select>

                    <a href="{{ route('machines.index', ['view' => $viewMode]) }}"
                       class="text-center bg-red-50 dark:bg-red-900/10 text-red-600 dark:text-red-400 text-xs font-black uppercase tracking-widest py-3 rounded-2xl hover:bg-red-100 transition-colors">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Content --}}
        <div class="relative">
            @if($machines->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] p-24 text-center border-2 border-dashed border-gray-100 dark:border-gray-700">
                    <div class="w-20 h-20 bg-gray-50 dark:bg-gray-900 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">No machines found</h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto font-medium">Try adjusting your filters or add a new machine to the inventory.</p>
                </div>
            @elseif($viewMode === 'grid')
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 animate-in zoom-in duration-300">
                    @foreach($machines as $machine)
                        <a href="{{ route('machines.show', $machine) }}"
                           class="group bg-white dark:bg-gray-800 p-6 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-xl shadow-gray-200/10 dark:shadow-none hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-1 transition-all">
                            <div class="flex items-start justify-between mb-6">
                                <div class="px-3 py-1 bg-gray-100 dark:bg-gray-900 text-[10px] font-black text-gray-500 dark:text-gray-400 rounded-lg group-hover:bg-cyan-500 group-hover:text-white transition-all">
                                    {{ $machine->machine_code }}
                                </div>
                                <x-status-badge :status="$machine->status" />
                            </div>
                            <div class="space-y-4">
                                <div class="mb-4">
                                    <p class="text-[10px] font-black text-cyan-600 dark:text-cyan-400 tracking-[0.2em] mb-1">
                                        {{ $machine->machineType?->category_code ?? 'UNCLASSIFIED' }}
                                    </p>
                                    @if(str_starts_with($machine->machine_name ?? '', '(Incomplete'))
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[9px] font-black uppercase tracking-wider mb-1">⚠ Incomplete</span>
                                    @endif
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-cyan-600 transition-colors leading-tight">
                                        {{ $machine->machineType?->description ?? (str_starts_with($machine->machine_name ?? '', '(Incomplete') ? $machine->machine_code : $machine->machine_name) }}
                                    </h3>
                                </div>
                                <div class="grid grid-cols-2 gap-4 pb-4 border-b border-gray-50 dark:border-gray-700">
                                    <div>
                                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Dept.</p>
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-400 truncate">{{ $machine->department?->name ?? 'Central' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">Purchase</p>
                                        <p class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $machine->purchase_date?->format('m/Y') ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-[2rem] shadow-2xl shadow-gray-200/20 dark:shadow-none overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50/50 dark:bg-gray-900/50">
                                    <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Code</th>
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Classification</th>
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Equipment Detail</th>
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Status</th>
                                    <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Registered On</th>
                                    <th class="px-6 py-5 text-right text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($machines as $machine)
                                    <tr class="group hover:bg-cyan-50/30 dark:hover:bg-cyan-900/10 transition-colors">
                                        <td class="px-8 py-5 whitespace-nowrap">
                                            <a href="{{ route('machines.show', $machine) }}" class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-black rounded-lg group-hover:bg-cyan-500 group-hover:text-white transition-all shadow-sm">
                                                {{ $machine->machine_code }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] font-black text-cyan-600 dark:text-cyan-400 tracking-tighter mb-0.5">{{ $machine->machineType?->category_code ?? '-' }}</span>
                                                <span class="text-xs font-bold text-gray-400 dark:text-gray-500">{{ trim(explode('|', $machine->category?->name ?? '')[1] ?? '-') ?: '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <a href="{{ route('machines.show', $machine) }}" class="flex flex-col gap-1">
                                                @if(str_starts_with($machine->machine_name ?? '', '(Incomplete'))
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-[9px] font-black uppercase tracking-wider w-fit">⚠ Incomplete — Edit to complete</span>
                                                @endif
                                                <span class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-xs group-hover:text-cyan-700 dark:group-hover:text-cyan-300">
                                                    {{ $machine->machineType?->description ?? (str_starts_with($machine->machine_name ?? '', '(Incomplete') ? $machine->machine_code : $machine->machine_name) }}
                                                </span>
                                                <span class="text-[10px] font-medium text-gray-400">{{ $machine->model ? "Model: {$machine->model}" : 'Generic' }}</span>
                                            </a>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap"><x-status-badge :status="$machine->status" /></td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-gray-600 dark:text-gray-300">{{ $machine->purchase_date?->format('M j, Y') ?? '-' }}</span>
                                                <span class="text-[10px] text-gray-400 font-medium">Added By System</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 whitespace-nowrap text-right">
                                            @if(auth()->user()->isAdmin())
                                                <button type="button" @click="deleteId = {{ $machine->id }}"
                                                        class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if($machines->hasPages())
                <div class="mt-12 overflow-hidden rounded-3xl border border-gray-100 dark:border-gray-700 bg-white/50 dark:bg-gray-800/50 backdrop-blur-md">
                    @include('components.pagination', ['paginator' => $machines])
                </div>
            @endif
        </div>

        {{-- Import Modal --}}
        <div x-show="importOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" @keydown.escape.window="importOpen = false">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="importOpen = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-[2rem] shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto border border-gray-100 dark:border-gray-700">
                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">Import Machines from Excel/CSV</h3>
                </div>
                <form method="POST" action="{{ route('machines.import') }}" enctype="multipart/form-data" class="p-8 space-y-4">
                    @csrf
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Upload an Excel (.xlsx) or CSV file. Only <strong>MACHINE CODE</strong> is required — all other columns are optional.
                    </p>
                    <div class="flex items-start gap-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700/50 rounded-2xl p-4">
                        <span class="text-amber-500 text-lg">💡</span>
                        <div class="text-xs text-amber-800 dark:text-amber-300">
                            <p class="font-bold uppercase tracking-wider mb-1">Partial Import Allowed</p>
                            <p>Missing details will be flagged and can be updated via Edit.</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500 bg-gray-50 dark:bg-gray-900/40 border border-dashed border-gray-200 dark:border-gray-700 rounded-2xl px-4 py-3">
                        <span>Need a template?</span>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('machines.template', ['format' => 'xlsx']) }}" class="text-cyan-600 dark:text-cyan-400 font-black hover:underline">Excel</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('machines.template', ['format' => 'csv']) }}" class="text-cyan-600 dark:text-cyan-400 font-black hover:underline">CSV</a>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Select File</label>
                        <input type="file" name="file" accept=".csv,.xlsx,.xls" required
                               class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 cursor-pointer">
                        @error('file')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="importOpen = false" class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-500">Cancel</button>
                        <button type="submit" class="px-8 py-2.5 bg-cyan-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-cyan-500 shadow-lg shadow-cyan-500/20">Start Import</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete Single --}}
        <div x-show="deleteId !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="deleteId = null"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-8 border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Delete Machine</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete this machine? This action cannot be undone.</p>
                <form method="POST" x-bind:action="'{{ url('/machines') }}/' + deleteId" class="flex justify-end gap-3">
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
                <h3 class="text-lg font-black text-red-600 mb-2">Delete All Machines</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">DANGER: This will permanently delete EVERY machine in your inventory. This action is irreversible.</p>
                <form method="POST" action="{{ route('machines.delete-all') }}" class="flex justify-end gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteAllOpen = false" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-danger">Yes, Delete Everything</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
