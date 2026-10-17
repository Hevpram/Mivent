@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mivent Admin | {{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="bg-background font-poppins text-black antialiased">
    <x-fmipa-sidebar />

    <div class="ml-[280px] min-h-screen">
        <x-fmipa-navbar />

        <main class="px-10 py-10 xl:px-16">
            {{ $slot }}
        </main>
    </div>

    {{ $scripts ?? '' }}
</body>
</html>
