<x-app-layout title="Select Category">
    <div class="min-h-[85vh] flex flex-col items-center justify-center p-6 animate-in fade-in zoom-in duration-500">
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl p-10 rounded-[2rem] shadow-2xl border border-white/20 dark:border-gray-700/50 w-full max-w-6xl">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-lg shadow-cyan-500/20">M</div>
                    <div>
                        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">Select Category</h1>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Step 1 — Define the machinery group</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($categories as $category)
                    @php
                        $parts = explode('|', $category->name);
                        $code = trim($parts[0] ?? '');
                        $label = trim($parts[1] ?? $category->name);
                    @endphp
                    <a href="{{ route('machines.create.type', $category) }}"
                       class="group relative flex flex-col items-center gap-4 p-8 rounded-[1.5rem] border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 hover:bg-gradient-to-b hover:from-cyan-50 hover:to-white dark:hover:from-cyan-900/10 dark:hover:to-gray-800/50 hover:border-cyan-400 dark:hover:border-cyan-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-1">
                        <div class="w-20 h-20 rounded-[1.25rem] bg-gray-50 dark:bg-gray-700 flex items-center justify-center group-hover:bg-white dark:group-hover:bg-gray-600 shadow-inner border border-transparent group-hover:border-cyan-100 dark:group-hover:border-cyan-900/30">
                            <svg class="w-10 h-10 text-gray-400 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3.75H4.875c-.621 0-1.125.504-1.125 1.125v4.5c0 .621.504 1.125 1.125 1.125H8.25m0-8.25h7.5m-7.5 0v8.25m0-8.25H12m7.5 0h2.625c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125H19.5m-7.5 0v8.25m0-8.25h7.5m-7.5 0H12"/></svg>
                        </div>
                        <div class="text-center">
                            <div class="inline-block px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-700 text-[10px] font-black text-gray-500 dark:text-gray-400 tracking-tighter mb-2 group-hover:bg-cyan-100 dark:group-hover:bg-cyan-900/40 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">{{ $code }}</div>
                            <h3 class="text-gray-900 dark:text-gray-100 font-bold text-base leading-tight group-hover:text-cyan-700 dark:group-hover:text-cyan-300 transition-colors">{{ $label }}</h3>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-16 pt-8 border-t border-gray-100 dark:border-gray-700/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('machines.index') }}" class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400 hover:text-cyan-600 transition-colors">← Back to Fleet List</a>
                <div class="flex gap-6">
                    @foreach(['Collaborate', 'Innovate', 'Deliver'] as $word)
                        <span class="text-[10px] font-black text-gray-300 dark:text-gray-600 uppercase tracking-[0.3em]">{{ $word }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
