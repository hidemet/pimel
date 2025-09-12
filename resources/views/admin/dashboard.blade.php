{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
  @section('title', 'Dashboard Admin - PIMEL')

  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <x-slot name="pageHeader">
    <x-layout.page-header title="Pannello Amministrazione" />
  </x-slot>

  <div class="container py-4 py-md-5">
    <div class="row g-4">
      {{-- Card Gestisci Articoli --}}
      <div class="col-md-6 col-lg-4">
        {{-- Aggiunto un wrapper <a> per rendere l'intera card cliccabile --}}
        {{--
          Nota: Bootstrap 5.3 ha `stretched-link` che rende l'intera card cliccabile per il PRIMO link all'interno.
          Qui usiamo un wrapper <a> per maggiore chiarezza e per rendere tutta l'area "visivamente" un link.
          Non è un pattern Bootstrap "ufficiale" per tutta la card, ma funziona e rende l'area più chiara.
          Potresti anche usare `<div class="card h-100 shadow-sm"> ... <a href="..." class="stretched-link"></a> </div>`
          e mettere il link a fine card. Il mio approccio rende il contenitore stesso un link.
        --}}
        <a
          href="{{ route('admin.articles.index') }}"
          class="card h-100 shadow-sm text-decoration-none text-dark"
        >
          <div
            class="card-body text-center d-flex flex-column justify-content-between"
          >
            <div class="mb-3">
              {{-- Icona e Descrizione sopra --}}
              <span class="material-symbols-outlined fs-1 text-dark mb-2">
                article
              </span>
              <h5 class="card-title fw-semibold">Gestisci Articoli</h5>
            </div>
            {{-- Non serve più un link "Vai a..." dato che tutta la card è cliccabile. --}}
          </div>
        </a>
      </div>

      {{-- Card Gestisci Rubriche --}}
      <div class="col-md-6 col-lg-4">
        <a
          href="{{ route('admin.rubrics.index') }}"
          class="card h-100 shadow-sm text-decoration-none text-dark"
        >
          <div
            class="card-body text-center d-flex flex-column justify-content-between"
          >
            <div class="mb-3">
              <span class="material-symbols-outlined fs-1 text-dark mb-2">
                category
              </span>
              <h5 class="card-title fw-semibold">Gestisci Rubriche</h5>
            </div>
          </div>
        </a>
      </div>

      {{-- Card Modera Commenti (Ancora disabilitata, ma con stile coerente) --}}
      <div class="col-md-6 col-lg-4">
        <a
          href="{{ route('admin.comments.index') }}"
          class="card h-100 shadow-sm text-decoration-none text-dark"
        >
          <div
            class="card-body text-center d-flex flex-column justify-content-between"
          >
            <div class="mb-3">
              <span class="material-symbols-outlined fs-1 text-dark mb-2">
                comment
              </span>
              <h5 class="card-title fw-semibold">Modera Commenti</h5>
            </div>
          </div>
        </a>
      </div>

      {{-- Card Gestisci Servizi (ORA ABILITATA) --}}
      <div class="col-md-6 col-lg-4">
        <a
          href="{{ route('admin.services.index') }}"
          class="card h-100 shadow-sm text-decoration-none text-dark"
        >
          <div
            class="card-body text-center d-flex flex-column justify-content-between"
          >
            <div class="mb-3">
              <span class="material-symbols-outlined fs-1 text-dark mb-2">
                design_services
              </span>
              <h5 class="card-title fw-semibold">Gestisci Servizi</h5>
            </div>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-lg-4">
        <a
          href="{{ route('admin.newsletter-subscriptions.index') }}"
          class="card h-100 shadow-sm text-decoration-none text-dark"
        >
          <div
            class="card-body text-center d-flex flex-column justify-content-between"
          >
            <div class="mb-3">
              <span class="material-symbols-outlined fs-1 text-dark mb-2">
                mark_email_unread
              </span>
              <h5 class="card-title fw-semibold">Gestisci Newsletter</h5>
            </div>
          </div>
        </a>
      </div>

      {{-- Card Visualizza Messaggi --}}
      <div class="col-md-6 col-lg-4">
        <a
          href="{{ route('admin.contact-messages.index') }}"
          class="card h-100 shadow-sm text-decoration-none text-dark"
        >
          <div
            class="card-body text-center d-flex flex-column justify-content-between"
          >
            <div class="mb-3">
              <span class="material-symbols-outlined fs-1 text-dark mb-2">
                forward_to_inbox
              </span>
              <h5 class="card-title fw-semibold">Visualizza Messaggi</h5>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</x-app-layout>
