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
<body class="bg-white bg-[linear-gradient(to_bottom,var(--color-background)_0,var(--color-background)_709px,white_809px)] font-poppins text-black antialiased">
    <x-fmipa-sidebar />

    <div class="ml-[249px] flex min-h-screen flex-col">
        <x-fmipa-navbar />

        <main class="flex-1 px-[73px] pb-[60px] pt-[67px]">
            {{ $slot }}
        </main>

        <x-admin-footer />
    </div>

    <x-toast />
    {{ $scripts ?? '' }}
</body>
</html>
