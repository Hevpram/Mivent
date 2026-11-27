@props(['title' => null, 'navbar' => true, 'footer' => true])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? 'Mivent | ' . $title : 'Mivent' }}</title>
    <link rel="icon" href="{{ asset('mivent_assets/logoColor.png') }}">
    {{-- tandai JS aktif sebelum render agar animasi domino tidak "berkedip" --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="bg-background font-poppins text-black antialiased">
    @if ($navbar)
        <x-navbar />
    @endif

    <main>
        {{ $slot }}
    </main>

    @if ($footer)
        <x-footer />
    @endif

    <x-toast />
    {{ $scripts ?? '' }}
</body>
</html>
