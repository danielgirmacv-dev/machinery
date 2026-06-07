@props([
    'show'       => 'showModal',
    'maxWidth'   => 'max-w-2xl',
    'title'      => '',
    'icon'       => null,
    'iconColor'  => 'from-cyan-600 to-blue-700',
])

<div
    x-show="{{ $show }}"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @keydown.escape.window="{{ $show }} = false"
>
    {{-- Backdrop --}}
    <div
        class="fixed inset-0 bg-gray-950/60 backdrop-blur-sm"
        @click="{{ $show }} = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
    ></div>

    {{-- Panel --}}
    <div class="relative {{ $maxWidth }} w-full max-h-[92vh] flex flex-col bg-white dark:bg-gray-900 rounded-[2rem] shadow-2xl border border-gray-100/80 dark:border-gray-700/80 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center gap-4 px-8 py-6 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 shrink-0">
            @if($icon)
                <div class="p-2.5 rounded-xl bg-gradient-to-br {{ $iconColor }} text-white shadow-lg shrink-0">
                    {!! $icon !!}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                {{ $title }}
            </div>
            <button
                type="button"
                @click="{{ $show }} = false"
                class="ml-auto p-2 rounded-xl text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all shrink-0"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Scrollable body --}}
        <div class="overflow-y-auto flex-1">
            {{ $slot }}
        </div>
    </div>
</div>
