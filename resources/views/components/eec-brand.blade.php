@props([
    'size' => 'md',
    'showWordmark' => true,
])

@php
    $sizes = [
        'sm' => ['badge' => 'h-8 w-8', 'mark' => 'h-4 w-auto', 'text' => 'text-lg', 'gap' => 'gap-2.5'],
        'md' => ['badge' => 'h-9 w-9', 'mark' => 'h-5 w-auto', 'text' => 'text-xl', 'gap' => 'gap-3'],
        'lg' => ['badge' => 'h-12 w-12', 'mark' => 'h-7 w-auto', 'text' => 'text-3xl', 'gap' => 'gap-3.5'],
        'xl' => ['badge' => 'h-16 w-16', 'mark' => 'h-9 w-auto', 'text' => 'text-5xl', 'gap' => 'gap-4'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center ' . $s['gap']]) }}>
    <div class="{{ $s['badge'] }} flex shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-eec-teal to-eec-cyan shadow-md shadow-eec-cyan/25 ring-1 ring-white/20">
        <x-eec-mark class="{{ $s['mark'] }} text-white" />
    </div>
    @if($showWordmark)
        <span class="{{ $s['text'] }} font-bold tracking-tight text-gray-900 dark:text-white leading-none">EEC</span>
    @endif
</div>
