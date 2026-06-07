<x-app-layout title="Select Machine Type">
    @php
        $parts = explode('|', $category->name);
        $code = trim($parts[0] ?? '');
        $label = trim($parts[1] ?? $category->name);
    @endphp

    <div class="min-h-[85vh] flex flex-col items-center justify-center p-6 animate-in slide-in-from-right duration-500">
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-10 rounded-[2rem] shadow-2xl border border-white/20 dark:border-gray-700/50 w-full max-w-5xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div class="flex items-center gap-6">
                    <a href="{{ route('machines.create') }}" class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 hover:bg-cyan-500 hover:text-white transition-all shadow-sm">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Select Machine Type</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-widest">{{ $code }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ $label }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($machineTypes->isEmpty())
                <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                    <p class="font-bold mb-4">No machine types found in this category.</p>
                    <a href="{{ route('settings.categories') }}" class="text-cyan-600 dark:text-cyan-400 font-black hover:underline">Configure in Technical Registry →</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($machineTypes as $mt)
                        <a href="{{ route('machines.create.form', [$category, $mt]) }}"
                           class="group flex items-center gap-4 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 bg-white/50 dark:bg-gray-800/30 hover:bg-white dark:hover:bg-gray-700 hover:border-cyan-500 dark:hover:border-cyan-400 transition-all duration-200 hover:shadow-lg">
                            <div class="w-12 h-12 rounded-xl bg-cyan-50 dark:bg-cyan-900/20 flex items-center justify-center text-cyan-600 dark:text-cyan-400 group-hover:bg-cyan-500 group-hover:text-white transition-all">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <div class="text-[10px] font-black text-cyan-600 dark:text-cyan-400 uppercase tracking-tighter">{{ $mt->category_code }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500">{{ $mt->eec_number }}</div>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors truncate">{{ $mt->description }}</h4>
                            </div>
                            <div class="p-2 rounded-lg bg-gray-50 dark:bg-gray-900/50 opacity-0 group-hover:opacity-100 transition-all">
                                <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-10 flex items-center justify-between">
                    <span class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-widest italic">Found {{ $machineTypes->count() }} matches in this category</span>
                    <div class="flex gap-4">
                        @foreach(['EEC Standards', 'ISO 9001'] as $tag)
                            <span class="px-2 py-1 rounded bg-gray-100 dark:bg-gray-900 text-[9px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
