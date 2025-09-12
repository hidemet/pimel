<x-app-layout>
  @section('title', 'Mio Profilo - PIMEL')

  <x-layout.page-header
    title="Mio Profilo"
    subtitle="Gestisci le informazioni del tuo account."
  />

  <div class="container py-4 py-md-5">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-7">

        @if (session('status') === 'profile-updated')
          <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
          >
            {{ __('Profilo aggiornato con successo.') }}
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="alert"
              aria-label="Close"
            ></button>
          </div>
        @endif

        @if (session('status') === 'password-updated')
          <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
          >
            {{ __('Password aggiornata con successo.') }}
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="alert"
              aria-label="Close"
            ></button>
          </div>
        @endif


        <div class="card shadow-sm mb-4">
          <div class="card-header">
            <h2 class="h5 mb-0">{{ __('Informazioni Profilo') }}</h2>
          </div>
          <div class="card-body p-4">
            @include('profile.partials.update-profile-information-form')
          </div>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-header">
            <h2 class="h5 mb-0">{{ __('Aggiorna Password') }}</h2>
          </div>
          <div class="card-body p-4">
            @include('profile.partials.update-password-form')
          </div>
        </div>
        {{--CARD PER PREFERENZE NEWSLETTER --}}
        <div class="card shadow-sm mb-4">
          <div class="card-header">
            <h2 class="h5 mb-0">{{ __('Preferenze Newsletter') }}</h2>
          </div>
          <div class="card-body p-4">
            <p class="text-muted small mb-3">
              Qui puoi aggiornare le categorie di argomenti che desideri
              ricevere tramite la nostra newsletter o annullare la tua
              iscrizione.
            </p>
            <a
              href="{{ route('profile.newsletter.edit') }}"
              class="btn btn-outline-primary"
            >
              <span class="material-symbols-outlined fs-6 align-middle me-1">
                settings
              </span>
              Gestisci Preferenze Newsletter
            </a>
          </div>
        </div>

        <div class="card shadow-sm">
          <div class="card-header">
            <h2 class="h5 mb-0">{{ __('Elimina Account') }}</h2>
          </div>
          <div class="card-body p-4">
            @include('profile.partials.delete-user-form')
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
