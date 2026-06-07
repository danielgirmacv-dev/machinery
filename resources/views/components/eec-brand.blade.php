@props([
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => ['mark' => 'h-7 w-auto', 'text' => 'text-xl', 'gap' => 'gap-2.5'],
        'md' => ['mark' => 'h-9 w-auto', 'text' => 'text-3xl', 'gap' => 'gap-3'],
        'lg' => ['mark' => 'h-12 w-auto', 'text' => 'text-[2.5rem]', 'gap' => 'gap-4'],
        'xl' => ['mark' => 'h-16 w-auto', 'text' => 'text-5xl', 'gap' => 'gap-5'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center ' . $s['gap']]) }}>
    <x-eec-mark class="{{ $s['mark'] }} shrink-0 text-eec-cyan" />
    <span class="{{ $s['text'] }} font-bold tracking-tight text-current leading-none">EEC</span>
</div>
