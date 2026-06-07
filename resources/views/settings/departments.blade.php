<x-app-layout title="Departments">
    <div class="space-y-8 animate-in slide-in-from-bottom duration-500" x-data="{
        showModal: false,
        deleteId: null,
        editId: null,
        form: { name: '', code: '' },
        openCreate() { this.editId = null; this.form = { name: '', code: '' }; this.showModal = true; },
        openEdit(id, name, code) { this.editId = id; this.form = { name, code }; this.showModal = true; }
    }">
        <div class="relative overflow-hidden bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl p-8 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-6">
                    <div class="p-4 rounded-[1.5rem] bg-gradient-to-br from-indigo-600 to-indigo-800 text-white shadow-xl">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008H17.25v-.008Zm0 3h.008v.008H17.25v-.008Zm0 3h.008v.008H17.25v-.008Z"/></svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-400">Organizational Structure</span>
                        <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Departments</h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Manage operational units and cost centers.</p>
                    </div>
                </div>
                @if(auth()->user()->canEdit())
                    <button type="button" @click="openCreate()" class="flex items-center gap-3 px-6 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-2xl hover:scale-105 transition-all shadow-xl">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        <span class="font-black text-xs uppercase tracking-widest">New Department</span>
                    </button>
                @endif
            </div>
        </div>

        @if($departments->isEmpty())
            <div class="text-center py-24 bg-white dark:bg-gray-800 rounded-[2.5rem] border-2 border-dashed border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Departments Registered</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto mb-8">Establish your first operational unit to assign assets and costs.</p>
                @if(auth()->user()->canEdit())
                    <button type="button" @click="openCreate()" class="btn-primary px-8">Create Department</button>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($departments as $department)
                    <div class="group relative bg-white dark:bg-gray-800 p-8 rounded-[2rem] shadow-xl hover:shadow-2xl transition-all border border-gray-100 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-6">
                            <div class="p-3 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008H17.25v-.008Z"/></svg>
                            </div>
                            <div class="flex items-center gap-1.5 px-3 py-1 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-100 dark:border-gray-700">
                                <span class="text-[10px] font-black text-gray-400 uppercase">Assets</span>
                                <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">{{ $department->machines_count }}</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-indigo-100 dark:bg-indigo-900/50 text-[10px] font-black text-indigo-700 dark:text-indigo-300 uppercase mb-2 inline-block">{{ $department->code ?? 'NO-CODE' }}</span>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors mb-4">{{ $department->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">This unit manages {{ $department->machines_count }} assets across the operational infrastructure.</p>
                        <div class="flex items-center justify-between pt-6 border-t border-gray-50 dark:border-gray-700">
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">ORG-ID: {{ str_pad($department->id, 4, '0', STR_PAD_LEFT) }}</span>
                            @if(auth()->user()->canEdit())
                                <div class="flex gap-2">
                                    <button type="button" @click="openEdit({{ $department->id }}, '{{ addslashes($department->name) }}', '{{ addslashes($department->code ?? '') }}')" class="p-2.5 bg-white dark:bg-gray-700 rounded-xl border text-gray-400 hover:text-indigo-600 shadow-sm">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                    </button>
                                    @if(auth()->user()->isAdmin())
                                        <button type="button" @click="deleteId = {{ $department->id }}" class="p-2.5 bg-white dark:bg-gray-700 rounded-xl border text-gray-400 hover:text-red-600 shadow-sm">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if($departments->hasPages())
                <div class="mt-8">@include('components.pagination', ['paginator' => $departments])</div>
            @endif
        @endif

        {{-- Modal --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white" x-text="editId ? 'Edit Department' : 'Establish Department'"></h3>
                </div>
                <form method="POST" x-bind:action="editId ? '{{ url('/settings/departments') }}/' + editId : '{{ route('settings.departments.store') }}'" class="p-6 space-y-6">
                    @csrf
                    <template x-if="editId"><input type="hidden" name="_method" value="PUT"></template>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Department Name *</label>
                        <input type="text" name="name" x-model="form.name" required class="input h-12 mt-1 font-bold @error('name') input-error @enderror">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Department Code</label>
                        <input type="text" name="code" x-model="form.code" class="input h-12 mt-1 font-bold">
                    </div>
                    <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="showModal = false" class="flex-1 h-12 rounded-2xl font-black text-xs uppercase text-gray-500">Discard</button>
                        <button type="submit" class="flex-[2] h-12 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase" x-text="editId ? 'Apply Identity' : 'Establish Unit'"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete --}}
        <div x-show="deleteId !== null" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="deleteId = null"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-8">
                <h3 class="text-lg font-black mb-2">Dissolve Department</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">This action will fail if the department has associated machines.</p>
                <form method="POST" x-bind:action="'{{ url('/settings/departments') }}/' + deleteId" class="flex justify-end gap-3">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteId = null" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-danger">Dissolve Unit</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
