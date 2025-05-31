<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PIMEL') }} - @yield('title', 'Pedagogia in Movimento')</title>
    @vite(['resources/scss/custom.scss', 'resources/js/app.js'])
    @stack('meta')
</head>

<body class="{{ $bodyClass }}"> {{-- Applica la classe al body --}}
    @if ($useAdminNavigation)
        <x-layout.admin-header /> {{-- Usa l'header admin --}}
    @else
        <x-layout.header :navBgClass="$navBgClass" /> {{-- Usa l'header pubblico (rimosso :isAdminArea) --}}
    @endif
    
    @isset($pageHeader)
        {{ $pageHeader }}
    @endisset

    <main class="flex-grow-1 w-100 {{ $contentClass }}">
        {{ $slot }}
    </main>


    @isset($afterMainFullWidthSection)
        {{ $afterMainFullWidthSection }}
    @endisset

    <x-layout.footer />

    {{-- Contenitore per i Toast --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
        {{-- Toast generico --}}
        <div id="sessionToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true"
            data-bs-delay="5000">
            <div class="toast-header">
                {{-- Icona e Titolo verranno impostati da JS --}}
                <span class="toast-icon me-2"></span>
                <strong class="me-auto toast-title">Notifica</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{-- Messaggio verrà impostato da JS --}}
            </div>
        </div>
    </div>
    @stack('scripts')

    {{-- Script per mostrare i toast dalla sessione --}}
    @if (session('toast'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toastData = @json(session('toast'));
                const sessionToastEl = document.getElementById('sessionToast');

                if (sessionToastEl && toastData) {
                    const toastTitleEl = sessionToastEl.querySelector('.toast-title');
                    const toastBodyEl = sessionToastEl.querySelector('.toast-body');
                    const toastIconEl = sessionToastEl.querySelector('.toast-icon');
                    const toastHeader = sessionToastEl.querySelector('.toast-header');

                    toastTitleEl.textContent = toastData.title || 'Notifica';
                    toastBodyEl.textContent = toastData.message || '';

                    // Rimuovi classi di colore precedenti dall'header
                    toastHeader.classList.remove('bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'text-white');
                    let iconHtml = '';

                    switch (toastData.type) {
                        case 'success':
                            toastHeader.classList.add('bg-success', 'text-white');
                            iconHtml = '<i class="bi bi-check-circle-fill"></i>';
                            break;
                        case 'error':
                            toastHeader.classList.add('bg-danger', 'text-white');
                            iconHtml = '<i class="bi bi-x-circle-fill"></i>';
                            break;
                        case 'warning':
                            toastHeader.classList.add('bg-warning', 'text-dark'); // text-dark per contrasto su giallo
                            iconHtml = '<i class="bi bi-exclamation-triangle-fill"></i>';
                            break;
                        case 'info':
                        default:
                            toastHeader.classList.add('bg-info', 'text-white');
                            iconHtml = '<i class="bi bi-info-circle-fill"></i>';
                            break;
                    }
                    toastIconEl.innerHTML = iconHtml;

                    const bsToast = new bootstrap.Toast(sessionToastEl);
                    bsToast.show();
                }
            });
        </script>
    @endif
</body>

</html>
