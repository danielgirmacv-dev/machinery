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
                        $normalizedCode = strtoupper(preg_replace('/\s+/', '', $code) ?? '');
                        $normalizedCode = str_replace(['_', '.'], '-', $normalizedCode);
                        if (str_starts_with($normalizedCode, 'EEC') && !str_starts_with($normalizedCode, 'EEC-')) {
                            $normalizedCode = preg_replace('/^EEC/', 'EEC-', $normalizedCode) ?? $normalizedCode;
                        }

                        $iconTheme = match ($normalizedCode) {
                            'EEC-10' => ['style' => 'background: linear-gradient(135deg, #0ea5e9, #2563eb)', 'icon' => 'truck'],
                            'EEC-20' => ['style' => 'background: linear-gradient(135deg, #f59e0b, #ea580c)', 'icon' => 'bulldozer'],
                            'EEC-30' => ['style' => 'background: linear-gradient(135deg, #6366f1, #7c3aed)', 'icon' => 'truck-heavy'],
                            'EEC-40' => ['style' => 'background: linear-gradient(135deg, #10b981, #0d9488)', 'icon' => 'trailer'],
                            'EEC-50' => ['style' => 'background: linear-gradient(135deg, #f43f5e, #ec4899)', 'icon' => 'factory'],
                            'EEC-60' => ['style' => 'background: linear-gradient(135deg, #f97316, #dc2626)', 'icon' => 'road'],
                            'EEC-70' => ['style' => 'background: linear-gradient(135deg, #06b6d4, #2563eb)', 'icon' => 'tool'],
                            'EEC-80' => ['style' => 'background: linear-gradient(135deg, #84cc16, #16a34a)', 'icon' => 'leaf'],
                            'EEC-90' => ['style' => 'background: linear-gradient(135deg, #64748b, #374151)', 'icon' => 'wrench'],
                            'EEC-A1' => ['style' => 'background: linear-gradient(135deg, #d946ef, #9333ea)', 'icon' => 'gear'],
                            default => ['style' => 'background: linear-gradient(135deg, #06b6d4, #2563eb)', 'icon' => 'grid'],
                        };

                        $iconSvg = match ($iconTheme['icon']) {
                            'truck' => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5A2.25 2.25 0 0 1 6 5.25h8.25A2.25 2.25 0 0 1 16.5 7.5v1.875h1.902c.497 0 .974.198 1.326.549l1.348 1.35c.352.35.549.827.549 1.324v2.652A2.25 2.25 0 0 1 19.375 17.5H18a2.25 2.25 0 1 0-4.5 0h-3a2.25 2.25 0 1 0-4.5 0H6A2.25 2.25 0 0 1 3.75 15.25V7.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 11.25h5.25"/></svg>',
                            'bulldozer' => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z"/></svg>',
                            'truck-heavy' => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.5A2.25 2.25 0 0 1 4.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v8.25H4.5A2.25 2.25 0 0 1 2.25 13.5V7.5Zm13.5 2.25h3.379c.398 0 .779.158 1.06.44l1.12 1.12c.281.282.44.663.44 1.061v3.379h-6V9.75Zm-9 8.25a1.5 1.5 0 1 0 0 .001m10.5-.001a1.5 1.5 0 1 0 0 .001"/></svg>',
                            'trailer' => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v10.5a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V6.75a1.5 1.5 0 0 1 1.5-1.5Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3.75h5.25"/></svg>',
                            'factory' => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>',
                            'road' => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3.75h7.5l-2.25 16.5h-3L8.25 3.75Zm3.75 3v2.25m0 3v2.25m0 3v2.25"/></svg>',
                            'tool' => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766"/></svg>',
                            'leaf' => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 19.5c9 0 12-7.5 12-15-7.5 0-15 3-15 12 0 1.657 1.343 3 3 3Zm0 0c0-3 3-6 6-6"/></svg>',
                            'wrench' => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.7 6.3 3 3m-8.4 8.4-3-3m1.5-1.5 6.9-6.9a3.182 3.182 0 1 1 4.5 4.5l-6.9 6.9a3.182 3.182 0 1 1-4.5-4.5Z"/></svg>',
                            'gear' => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 3.75h3l.6 2.217a6.75 6.75 0 0 1 1.534.891l2.15-.899 1.5 2.598-1.75 1.48c.087.485.132.985.132 1.5s-.045 1.015-.132 1.5l1.75 1.48-1.5 2.598-2.15-.9a6.75 6.75 0 0 1-1.534.892l-.6 2.217h-3l-.6-2.217a6.75 6.75 0 0 1-1.534-.891l-2.15.899-1.5-2.598 1.75-1.48A8.22 8.22 0 0 1 6 12c0-.515.045-1.015.132-1.5l-1.75-1.48 1.5-2.598 2.15.9a6.75 6.75 0 0 1 1.534-.892L10.5 3.75Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25A2.25 2.25 0 1 0 12 9.75a2.25 2.25 0 0 0 0 4.5Z"/></svg>',
                            default => '<svg class="w-9 h-9" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5h6v6h-6v-6Zm9 0h6v6h-6v-6Zm-9 9h6v6h-6v-6Zm9 0h6v6h-6v-6Z"/></svg>',
                        };
                    @endphp
                    <a href="{{ route('machines.create.type', $category) }}"
                       class="group relative flex flex-col items-center gap-5 p-8 rounded-[1.75rem] border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/50 hover:border-cyan-400 dark:hover:border-cyan-500/50 transition-all duration-300 hover:shadow-2xl hover:shadow-cyan-500/10 hover:-translate-y-1.5">
                        <div class="w-24 h-24 rounded-[1.5rem] flex items-center justify-center text-white shadow-xl shadow-black/15 group-hover:scale-110 transition-transform duration-300" style="{{ $iconTheme['style'] }}">
                            {!! $iconSvg !!}
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
