<x-app-layout title="Technical Registry">
    @php
        $categoryGroups = $categories->map(fn ($c) => trim(explode('|', $c->name)[0] ?? ''))->unique()->filter()->sort()->values();
    @endphp

    <div class="space-y-8 animate-in slide-in-from-bottom duration-500" x-data="{
        showModal: false,
        editId: null,
        deleteId: null,
        form: {
            parent_category: '',
            machine_group: '',
            category_id_code: '',
            description: '',
            eec_config: ''
        },
        openCreate() {
            this.editId = null;
            this.form = { parent_category: '', machine_group: '', category_id_code: '', description: '', eec_config: '' };
            this.showModal = true;
        },
        openEdit(data) {
            this.editId = data.id;
            this.form = { ...data };
            this.showModal = true;
        }
    }">
        <div class="relative overflow-hidden bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10">
                <div class="flex items-center gap-6">
                    <div class="p-4 rounded-[1.5rem] bg-gradient-to-br from-indigo-600 to-indigo-800 text-white shadow-xl">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm0 5.25h.007v.008H3.75v-.008Zm0 5.25h.007v.008H3.75v-.008Z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-400">Inventory Distribution</span>
                        </div>
                        <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Technical Registry</h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Full management of EEC classifications and equipment specifications.</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4">
                    <form method="GET" action="{{ route('settings.categories') }}" class="flex flex-wrap gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search registry..."
                               class="h-12 pl-4 pr-6 rounded-xl bg-gray-50 dark:bg-gray-900/50 border-transparent focus:ring-indigo-500 font-bold text-sm min-w-[200px]">
                        <select name="category" onchange="this.form.submit()" class="h-12 px-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border-transparent font-bold text-sm min-w-[160px]">
                            <option value="all">All Categories</option>
                            @foreach($categoryGroups as $group)
                                <option value="{{ $group }}" @selected(request('category') === $group)>{{ $group }}</option>
                            @endforeach
                        </select>
                    </form>

                    @if(auth()->user()->canEdit())
                        <button type="button" @click="openCreate()" class="flex items-center gap-3 px-6 py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl hover:scale-105 transition-all shadow-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            <span class="font-black text-xs uppercase tracking-widest">Add Classification</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-8 py-6 text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Category</th>
                            <th class="px-8 py-6 text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Machine Group</th>
                            <th class="px-8 py-6 text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Category ID</th>
                            <th class="px-8 py-6 text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Machine Description</th>
                            <th class="px-8 py-6 text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">EEC Number</th>
                            <th class="px-8 py-6 text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @forelse($machineTypes as $type)
                            @php
                                $parts = explode('|', $type->category?->name ?? '');
                                $parentCode = trim($parts[0] ?? '');
                                $parentName = trim($parts[1] ?? '');
                            @endphp
                            <tr class="group hover:bg-indigo-50/20 dark:hover:bg-indigo-900/10 transition-colors">
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 text-[10px] font-black rounded-lg">{{ $parentCode }}</span>
                                </td>
                                <td class="px-8 py-6 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-tight">{{ $parentName }}</td>
                                <td class="px-8 py-6 text-sm font-black text-indigo-600 dark:text-indigo-400 italic">{{ $type->category_code }}</td>
                                <td class="px-8 py-6 text-sm font-medium text-gray-500 dark:text-gray-400">{{ $type->description }}</td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300 font-mono">{{ $type->eec_number }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @if(auth()->user()->canEdit())
                                            <button type="button"
                                                    @click="openEdit({ id: {{ $type->id }}, parent_category: '{{ addslashes($parentCode) }}', machine_group: '{{ addslashes($parentName) }}', category_id_code: '{{ addslashes($type->category_code) }}', description: '{{ addslashes($type->description) }}', eec_config: '{{ addslashes($type->eec_number) }}' })"
                                                    class="p-2 bg-white dark:bg-gray-700 rounded-xl border border-gray-100 dark:border-gray-600 text-gray-400 hover:text-indigo-600 shadow-sm">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                            </button>
                                        @endif
                                        @if(auth()->user()->isAdmin())
                                            <button type="button" @click="deleteId = {{ $type->id }}" class="p-2 bg-white dark:bg-gray-700 rounded-xl border text-gray-400 hover:text-red-500 shadow-sm">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-8 py-16 text-center text-gray-500 dark:text-gray-400">No classifications found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($machineTypes->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                    @include('components.pagination', ['paginator' => $machineTypes])
                </div>
            @endif
        </div>

        {{-- Create/Edit Modal --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white" x-text="editId ? 'Refine Technical Mapping' : 'Establish Technical Mapping'"></h3>
                </div>
                <form method="POST" x-bind:action="editId ? '{{ url('/settings/categories') }}/' + editId : '{{ route('settings.categories.store') }}'" class="p-6 space-y-6">
                    @csrf
                    <template x-if="editId"><input type="hidden" name="_method" value="PUT"></template>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Category</label>
                            <input type="text" name="parent_category" x-model="form.parent_category" placeholder="e.g. EEC-10" required class="input h-12 mt-1 font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Machine Group</label>
                            <input type="text" name="machine_group" x-model="form.machine_group" placeholder="e.g. Heavy Vehicles" required class="input h-12 mt-1 font-bold">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Category ID</label>
                            <input type="text" name="category_id_code" x-model="form.category_id_code" placeholder="e.g. EEC 10-01" required class="input h-12 mt-1 font-bold">
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">EEC Number Configuration</label>
                            <input type="text" name="eec_config" x-model="form.eec_config" placeholder="e.g. EEC 10-01-001" required class="input h-12 mt-1 font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Machine Description</label>
                        <input type="text" name="description" x-model="form.description" placeholder="e.g. MOTOR CYCLE" required class="input h-12 mt-1 font-bold">
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="showModal = false" class="flex-1 h-12 rounded-2xl font-black text-xs uppercase text-gray-500">Discard</button>
                        <button type="submit" class="flex-[2] h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-xs uppercase shadow-xl" x-text="editId ? 'Apply Deployment' : 'Establish Record'"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete Confirm --}}
        <div x-show="deleteId !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="deleteId = null"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-8">
                <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2">Registry Sanitation</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Warning: You are about to purge this classification from the master technical registry. Proceed?</p>
                <form method="POST" x-bind:action="'{{ url('/settings/categories') }}/' + deleteId" class="flex justify-end gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteId = null" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-danger">Confirm Purge</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
