<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PIMEL') }} - @yield('title', 'Autenticazione')</title>
    @vite(['resources/scss/custom.scss', 'resources/js/app.js'])
</head>

<body class="bg-light d-flex flex-column h-100">
    <div class="container flex-grow-1"> {{-- flex-grow-1 per spingere il footer in basso --}}
        <div class="row justify-content-center align-items-center min-vh-100 py-5"> {{-- min-vh-100 per centrare verticalmente se poco contenuto --}}
            <div class="col-md-7 col-lg-6 col-xl-5">
                {{ $slot }}
            </div>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
