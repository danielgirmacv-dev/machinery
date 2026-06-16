@php
    $navLinkBase = 'group relative flex items-center gap-x-3 rounded-xl px-2.5 py-2.5 text-sm font-medium leading-6 transition-all duration-200';
    $navLinkActive = 'bg-gradient-to-r from-eec-cyan/10 to-eec-teal/5 text-eec-teal dark:from-eec-cyan/15 dark:to-eec-teal/10 dark:text-eec-cyan';
    $navLinkInactive = 'text-gray-500 hover:bg-gray-100/80 hover:text-eec-teal dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-eec-cyan';
    $iconBase = 'flex h-8 w-8 shrink-0 items-center justify-center rounded-lg transition-all duration-200';
    $iconActive = 'bg-gradient-to-br from-eec-cyan/20 to-eec-teal/10 text-eec-cyan dark:from-eec-cyan/25 dark:to-eec-teal/15 shadow-sm';
    $iconInactive = 'text-gray-400 group-hover:text-eec-cyan dark:group-hover:text-eec-cyan';
@endphp

<div class="flex flex-1 flex-col overflow-y-auto px-3 py-4 gap-y-5 custom-scrollbar">
    <ul role="list" class="space-y-1">
        @foreach($navigation as $item)
            <li>
                <a href="{{ $item['href'] }}"
                   @if($collapsed) :class="$store.sidebar.collapsed ? 'justify-center px-2' : ''" @endif
                   @click="$store.sidebar.closeMobile()"
                   title="{{ $item['name'] }}"
                   class="{{ $navLinkBase }} {{ $item['active'] ? $navLinkActive : $navLinkInactive }}">
                    @if($item['active'])
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-5 rounded-r-full bg-eec-cyan"></div>
                    @endif
                    <span class="{{ $iconBase }} {{ $item['active'] ? $iconActive : $iconInactive }}">
                        <x-icons.nav-icon :name="$item['icon']" class="w-[1.125rem] h-[1.125rem]" />
                    </span>
                    <span class="truncate" @if($collapsed) :class="$store.sidebar.collapsed ? 'hidden' : 'block'" @endif>{{ $item['name'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <div class="border-t border-gray-100 dark:border-white/[0.06]"></div>

    <div>
        <div class="text-[10px] font-bold leading-6 text-gray-400 dark:text-gray-500 px-2.5 mb-2 uppercase tracking-widest"
             @if($collapsed) :class="$store.sidebar.collapsed ? 'sr-only' : 'block'" @endif>Settings</div>
        <ul role="list" class="space-y-1">
            @foreach($settingsNavigation as $item)
                <li>
                    <a href="{{ $item['href'] }}"
                       @if($collapsed) :class="$store.sidebar.collapsed ? 'justify-center px-2' : ''" @endif
                       @click="$store.sidebar.closeMobile()"
                       title="{{ $item['name'] }}"
                       class="{{ $navLinkBase }} {{ $item['active'] ? $navLinkActive : $navLinkInactive }}">
                        @if($item['active'])
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-5 rounded-r-full bg-eec-cyan"></div>
                        @endif
                        <span class="{{ $iconBase }} {{ $item['active'] ? $iconActive : $iconInactive }}">
                            <x-icons.nav-icon :name="$item['icon']" class="w-[1.125rem] h-[1.125rem]" />
                        </span>
                        <span class="truncate" @if($collapsed) :class="$store.sidebar.collapsed ? 'hidden' : 'block'" @endif>{{ $item['name'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    @if($isAdmin)
        <div>
            <div class="border-t border-gray-100 dark:border-white/[0.06] mb-4"></div>
            <div class="text-[10px] font-bold leading-6 text-gray-400 dark:text-gray-500 px-2.5 mb-2 uppercase tracking-widest"
                 @if($collapsed) :class="$store.sidebar.collapsed ? 'sr-only' : 'block'" @endif>Admin</div>
            <ul role="list" class="space-y-1">
                @foreach($adminNavigation as $item)
                    <li>
                        <a href="{{ $item['href'] }}"
                           @if($collapsed) :class="$store.sidebar.collapsed ? 'justify-center px-2' : ''" @endif
                           @click="$store.sidebar.closeMobile()"
                           title="{{ $item['name'] }}"
                           class="{{ $navLinkBase }} {{ $item['active'] ? $navLinkActive : $navLinkInactive }}">
                            @if($item['active'])
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] h-5 rounded-r-full bg-eec-cyan"></div>
                            @endif
                            <span class="{{ $iconBase }} {{ $item['active'] ? $iconActive : $iconInactive }}">
                                <x-icons.nav-icon :name="$item['icon']" class="w-[1.125rem] h-[1.125rem]" />
                            </span>
                            <span class="truncate" @if($collapsed) :class="$store.sidebar.collapsed ? 'hidden' : 'block'" @endif>{{ $item['name'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div class="px-3 py-3 border-t border-gray-100 dark:border-white/[0.06] text-[10px] font-semibold text-gray-400 dark:text-gray-600 text-center uppercase tracking-wider"
     @if($collapsed) :class="$store.sidebar.collapsed ? 'sr-only' : 'block'" @endif>
    v1.2.0-beta
</div>
