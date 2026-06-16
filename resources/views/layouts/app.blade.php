<!DOCTYPE html>
<html lang="en" x-data x-init="$store.theme.init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'EEC' }} — Fleet Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <div class="min-h-screen bg-slate-50 dark:bg-[#0a0f1a] transition-colors duration-500">
        {{-- Subtle dot pattern background --}}
        <div class="fixed inset-0 z-0 pointer-events-none opacity-[0.03] dark:opacity-[0.04]"
             style="background-image: radial-gradient(circle, currentColor 1px, transparent 1px); background-size: 24px 24px;"></div>
        {{-- Gradient orbs --}}
        <div class="fixed top-0 left-0 w-[600px] h-[600px] bg-eec-cyan/5 dark:bg-eec-cyan/[0.03] rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2 pointer-events-none z-0"></div>
        <div class="fixed bottom-0 right-0 w-[500px] h-[500px] bg-eec-teal/5 dark:bg-eec-teal/[0.03] rounded-full blur-[120px] translate-x-1/3 translate-y-1/3 pointer-events-none z-0"></div>

        <div class="relative z-10" x-data>
            @include('components.layout.sidebar')
            <div class="transition-all duration-300" :class="$store.sidebar.collapsed ? 'lg:pl-[4.5rem]' : 'lg:pl-64'">
                @include('components.layout.header')
                <main class="py-8">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        @include('components.flash-messages')
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>
</body>
</html>
