<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PIMEL') }} - @yield('title', 'Pedagogia in Movimento')</title>
    @vite(['resources/scss/custom.scss', 'resources/js/app.js'])
    @stack('meta') {{-- Per i meta tag specifici della pagina (es. Open Graph) --}}
</head>

<body> {{-- antialiased è di Breeze, puoi tenerlo o rimuoverlo --}}
    <x-layout.header />
    <!-- Page Content -->
    <main class="flex-grow-1">
        {{ $slot }}
    </main>

    <x-layout.footer />

    @stack('scripts')
</body>

</html>
