@php
    $user = auth()->user();
    $isAdmin = $user->isAdmin();
    $canEdit = $user->canEdit();

    $navigation = [
        ['name' => 'Dashboard', 'href' => route('dashboard'), 'active' => request()->routeIs('dashboard'), 'icon' => 'dashboard'],
        ['name' => 'Equipment', 'href' => route('machines.index'), 'active' => request()->routeIs('machines.*'), 'icon' => 'equipment'],
    ];

    if ($isAdmin) {
        $navigation[] = ['name' => 'Maintenance', 'href' => route('maintenance.index'), 'active' => request()->routeIs('maintenance.*'), 'icon' => 'maintenance'];
    }

    $settingsNavigation = [
        ['name' => 'Categories', 'href' => route('settings.categories'), 'active' => request()->routeIs('settings.categories*'), 'icon' => 'categories'],
        ['name' => 'Departments', 'href' => route('settings.departments'), 'active' => request()->routeIs('settings.departments*'), 'icon' => 'departments'],
        ['name' => 'Locations', 'href' => route('settings.locations'), 'active' => request()->routeIs('settings.locations*'), 'icon' => 'locations'],
    ];

    $adminNavigation = [
        ['name' => 'Users', 'href' => route('users.index'), 'active' => request()->routeIs('users.*'), 'icon' => 'users'],
        ['name' => 'Audit Log', 'href' => route('audit-logs.index'), 'active' => request()->routeIs('audit-logs.*'), 'icon' => 'audit-log'],
    ];
@endphp

{{-- Mobile sidebar --}}
<div x-show="$store.sidebar.mobileOpen" x-cloak class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
    <div x-show="$store.sidebar.mobileOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" @click="$store.sidebar.closeMobile()"></div>
    <div class="fixed inset-0 flex">
        <div x-show="$store.sidebar.mobileOpen" x-transition class="relative mr-16 flex w-full max-w-xs flex-1 flex-col bg-white dark:bg-gray-900">
            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                <button type="button" class="-m-2.5 p-2.5" @click="$store.sidebar.closeMobile()">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="flex h-16 shrink-0 items-center px-4 border-b border-gray-100 dark:border-gray-800">
                <x-eec-brand size="sm" />
            </div>
            @include('components.layout.sidebar-nav', ['collapsed' => false])
        </div>
    </div>
</div>

{{-- Desktop sidebar --}}
<div class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-50 lg:flex lg:flex-col lg:bg-white lg:dark:bg-gray-900 lg:border-r lg:border-gray-200/80 lg:dark:border-gray-800 lg:transition-all lg:duration-300"
     :class="$store.sidebar.collapsed ? 'lg:w-[4.5rem]' : 'lg:w-64'">
    <div class="flex h-16 shrink-0 items-center px-3 border-b border-gray-100 dark:border-gray-800 relative">
        <div class="transition-all duration-300 overflow-hidden" :class="$store.sidebar.collapsed ? 'opacity-0 invisible w-0' : 'opacity-100 visible w-full'">
            <x-eec-brand size="sm" class="px-1" />
        </div>
        <div class="absolute left-1/2 -translate-x-1/2 transition-opacity duration-300" :class="$store.sidebar.collapsed ? 'opacity-100 visible' : 'opacity-0 invisible'">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-eec-teal to-eec-cyan shadow-md shadow-eec-cyan/25 ring-1 ring-white/20">
                <x-eec-mark class="h-5 w-auto text-white" />
            </div>
        </div>
        <button type="button" @click="$store.sidebar.toggle()" class="absolute -right-3 top-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-full p-1.5 shadow-md hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-400 hover:text-eec-cyan transition-colors z-50 hidden lg:block">
            <svg x-show="!$store.sidebar.collapsed" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
            <svg x-show="$store.sidebar.collapsed" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
        </button>
    </div>
    @include('components.layout.sidebar-nav', ['collapsed' => true])
</div>
