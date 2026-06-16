<header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-x-4 bg-white/70 dark:bg-[#0a0f1a]/70 backdrop-blur-2xl border-b border-gray-200/50 dark:border-white/[0.06] px-4 sm:gap-x-6 sm:px-6 lg:px-8 transition-all duration-300">
    <button type="button" class="-m-2.5 p-2.5 text-gray-500 lg:hidden dark:text-gray-400 hover:text-eec-cyan transition-colors" @click="$store.sidebar.openMobile()">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
    </button>
    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <div class="flex flex-1"></div>
        <div class="flex items-center gap-x-3 lg:gap-x-4">
            {{-- Theme toggle --}}
            <button type="button" @click="$store.theme.toggle()" class="relative p-2.5 text-gray-400 hover:text-eec-cyan dark:text-gray-500 dark:hover:text-eec-cyan transition-all duration-300 rounded-xl hover:bg-gray-100/80 dark:hover:bg-white/5" aria-label="Toggle theme">
                <svg x-show="$store.theme.mode === 'light'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                <svg x-show="$store.theme.mode === 'dark'" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
            </button>

            <div class="h-6 w-px bg-gray-200 dark:bg-white/10"></div>

            {{-- User menu --}}
            <div class="relative" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="flex items-center gap-3 p-1.5 hover:bg-gray-100/80 dark:hover:bg-white/5 rounded-xl transition-all duration-300 group">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-eec-cyan to-eec-teal flex items-center justify-center text-white text-sm font-bold shadow-md shadow-eec-cyan/20">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="hidden lg:flex lg:items-center gap-1.5">
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-eec-cyan transition-colors">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 bg-gray-100 dark:bg-white/5 px-2 py-0.5 rounded-md capitalize">{{ auth()->user()->role }}</span>
                    </span>
                    <svg class="hidden lg:block h-4 w-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-2xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl py-2 shadow-xl shadow-gray-900/10 dark:shadow-black/30 ring-1 ring-gray-900/5 dark:ring-white/10">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-left text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
