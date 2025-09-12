<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  class="h-100"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta
      name="csrf-token"
      content="{{ csrf_token() }}"
    />

    <title>
      {{ config('app.name', 'PIMEL') }} -
      @yield('title', 'Pedagogia in Movimento')
    </title>
    @stack('meta')
  </head>

  <body class="d-flex flex-column h-100">
    <x-layout.header />

    @isset($pageHeader)
      {{ $pageHeader }}
    @endisset

    <main class= "flex-shrink-0 w-100">
      {{ $slot }}
    </main>

    <x-layout.footer />

    <x-auth.guest-modal
      id="guestActionModal"
      title="Accesso Richiesto"
      message="Per mettere un link o commentare, devi prima accedere o creare un account."
    />

    <div
      class="toast-container position-fixed bottom-0 end-0 p-3"
      style="z-index: 1080"
    >
      {{--
        @if (session('toast'))
        data-session-toast='{{ json_encode(session('toast')) }}'
        @endif
      --}}

      <div
        id="sessionToast"
        class="toast"
        role="alert"
        aria-live="assertive"
        aria-atomic="true"
        data-bs-delay="5000"
      >
        <div class="toast-header">
          <span class="toast-icon me-2"></span>
          <strong class="me-auto toast-title">Notifica</strong>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="toast"
            aria-label="Close"
          ></button>
        </div>
        <div class="toast-body"></div>
      </div>
    </div>

    @vite(['resources/scss/custom.scss', 'resources/js/app.js'])

    @stack('page-scripts')
    @stack('meta')
  </body>
</html>
