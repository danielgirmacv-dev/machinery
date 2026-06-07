<div class="flex flex-1 flex-col overflow-y-auto px-3 py-4 gap-y-6">
    <ul role="list" class="space-y-1">
        @foreach($navigation as $item)
            <li>
                <a href="{{ $item['href'] }}"
                   @if($collapsed) :class="$store.sidebar.collapsed ? 'justify-center' : ''" @endif
                   @click="$store.sidebar.closeMobile()"
                   class="group flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 transition-all {{ $item['active'] ? 'bg-cyan-50 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400' : 'text-gray-700 hover:bg-gray-50 hover:text-cyan-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-cyan-400' }}">
                    <span class="truncate" @if($collapsed) :class="$store.sidebar.collapsed ? 'hidden' : 'block'" @endif>{{ $item['name'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="border-t border-gray-100 dark:border-gray-700"></div>

    <div>
        <div class="text-xs font-semibold leading-6 text-gray-400 dark:text-gray-500 px-2 mb-2 uppercase tracking-wider" @if($collapsed) :class="$store.sidebar.collapsed ? 'hidden' : 'block'" @endif>Settings</div>
        <ul role="list" class="space-y-1">
            @foreach($settingsNavigation as $item)
                <li>
                    <a href="{{ $item['href'] }}" @click="$store.sidebar.closeMobile()"
                       class="group flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 transition-all {{ $item['active'] ? 'bg-cyan-50 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400' : 'text-gray-700 hover:bg-gray-50 hover:text-cyan-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-cyan-400' }}">
                        <span class="truncate" @if($collapsed) :class="$store.sidebar.collapsed ? 'hidden' : 'block'" @endif>{{ $item['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @if($isAdmin)
        <div>
            <div class="border-t border-gray-100 dark:border-gray-700 mb-4"></div>
            <div class="text-xs font-semibold leading-6 text-gray-400 dark:text-gray-500 px-2 mb-2 uppercase tracking-wider" @if($collapsed) :class="$store.sidebar.collapsed ? 'hidden' : 'block'" @endif>Admin</div>
            <ul role="list" class="space-y-1">
                @foreach($adminNavigation as $item)
                    <li>
                        <a href="{{ $item['href'] }}" @click="$store.sidebar.closeMobile()"
                           class="group flex items-center gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 transition-all {{ $item['active'] ? 'bg-cyan-50 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400' : 'text-gray-700 hover:bg-gray-50 hover:text-cyan-600 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-cyan-400' }}">
                            <span class="truncate" @if($collapsed) :class="$store.sidebar.collapsed ? 'hidden' : 'block'" @endif>{{ $item['name'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div class="p-4 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500 text-center" @if($collapsed) :class="$store.sidebar.collapsed ? 'opacity-0' : 'opacity-100'" @endif>
    v1.2.0-beta
</div>
