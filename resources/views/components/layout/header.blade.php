<header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 dark:bg-gray-800 dark:border-gray-700">
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden dark:text-gray-200" @click="$store.sidebar.openMobile()">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
    </button>
    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <div class="flex flex-1"></div>
        <div class="flex items-center gap-x-4 lg:gap-x-6">
            <button type="button" @click="$store.theme.toggle()" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" aria-label="Toggle theme">
                <svg x-show="$store.theme.mode === 'light'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/></svg>
                <svg x-show="$store.theme.mode === 'dark'" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>
            </button>

            <div class="relative" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="-m-1.5 flex items-center p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</span>
                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400 capitalize">({{ auth()->user()->role }})</span>
                    </span>
                </button>
                <div x-show="open" @click.outside="open = false" x-cloak x-transition class="absolute right-0 z-10 mt-2.5 w-40 origin-top-right rounded-md bg-white dark:bg-gray-800 py-2 shadow-lg ring-1 ring-gray-900/5 dark:ring-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-3 py-1 text-left text-sm leading-6 text-gray-900 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700">Sign out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
