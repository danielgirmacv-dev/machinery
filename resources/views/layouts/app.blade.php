<!DOCTYPE html>
<html lang="en" x-data x-init="$store.theme.init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'EEC' }} — Machine Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 bg-[url('/background.png')] bg-cover bg-fixed transition-colors duration-500">
        <div class="fixed inset-0 bg-gray-50/90 dark:bg-gray-900/90 z-0 pointer-events-none transition-colors duration-500"></div>

        <div class="relative z-10" x-data>
            @include('components.layout.sidebar')
            <div class="transition-all duration-300" :class="$store.sidebar.collapsed ? 'lg:pl-[4.5rem]' : 'lg:pl-64'">
                @include('components.layout.header')
                <main class="py-6">
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
