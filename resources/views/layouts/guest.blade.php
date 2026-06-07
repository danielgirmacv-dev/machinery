<!DOCTYPE html>
<html lang="en" x-data x-init="$store.theme.init()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — EEC Plant &amp; Equipment Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(config('services.turnstile.site_key'))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
</head>
<body>
    {{ $slot }}
</body>
</html>
