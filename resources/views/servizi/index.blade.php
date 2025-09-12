@push('page-scripts')
  @vite('resources/js/pages/servizi-index.js')
@endpush

<x-app-layout>
  @section('title', 'Servizi di Consulenza Pedagogica - PIMEL')

  <x-slot name="pageHeader">
    <x-layout.page-header title="Servizi di Consulenza" />
  </x-slot>

  <div class="container py-4 py-md-5">
    @if ($targetCategories->isEmpty())
      <div class="text-center py-5">
        <span class="material-symbols-outlined display-1 text-muted mb-3">
          sentiment_dissatisfied
        </span>
        <h2 class="h4">Nessun servizio disponibile al momento.</h2>
      </div>
    @else
      <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
          <div
            class="row row-cols-1 row-cols-md-2 g-4 g-xl-5 justify-content-center"
          >
            @foreach ($targetCategories as $category)
              @if ($category->services->isNotEmpty())
                <div class="col d-flex">
                  <section class="card shadow-sm w-100 flex-fill">
                    <div class="card-header text-center py-3 border-0">
                      <div class="mb-2">
                        @if ($category->icon_class)
                          <span
                            class="material-symbols-outlined fs-1 text-dark"
                          >
                            {{ $category->icon_class }}
                          </span>
                        @else
                          <span
                            class="material-symbols-outlined fs-1 text-dark"
                          >
                            category
                          </span>
                        @endif
                      </div>
                      <h2 class="h5 fw-semibold mb-0 text-dark">
                        {{ $category->name }}
                      </h2>
                    </div>
                    <div class="card-body d-flex flex-column">
                      @if ($category->description)
                        <p class="small text-muted mb-3">
                          {{ $category->description }}
                        </p>
                      @endif

                      <ul class="list-unstyled mb-0 mt-auto">
                        @foreach ($category->services as $service)
                          <li class="mb-2">
                            <button
                              type="button"
                              class="btn btn-link p-0 text-start fw-medium text-body text-decoration-none focus-ring w-100 service-modal-trigger"
                              data-bs-toggle="modal"
                              data-bs-target="#serviceDetailModal"
                              data-service-name="{{ $service->name }}"
                              data-service-description="{{ $service->description }}"
                              data-service-target-audience="{{ $service->target_audience }}"
                              data-service-objectives="{{ $service->objectives }}"
                              data-service-modalities="{{ $service->modalities }}"
                              data-service-contact-url="{{ route('contatti.form', ['service_of_interest' => $service->name]) }}"
                            >
                              <i
                                class="bi bi-check-circle text-primary me-2"
                              ></i>
                              {{ $service->name }}
                            </button>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                  </section>
                </div>
              @endif
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>

  {{-- Modale Unica --}}
  <div
    class="modal fade"
    id="serviceDetailModal"
    tabindex="-1"
    aria-labelledby="serviceDetailModalLabel"
    aria-hidden="true"
  >
    <div
      class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
    >
      <div class="modal-content border-0 shadow">
        <div class="modal-header bg-light border-0">
          <h5
            class="modal-title fw-bold text-primary mb-0"
            id="serviceDetailModalLabel"
          >
            {{-- Titolo inserito da JS --}}
          </h5>
          <button
            type="button"
            class="btn-close focus-ring"
            data-bs-dismiss="modal"
            aria-label="Chiudi"
          ></button>
        </div>
        <div class="modal-body p-4">
          <div
            id="modalDescriptionSection"
            class="mb-4"
            style="display: none"
          >
            <h6 class="fw-semibold d-flex align-items-center mb-2 text-muted">
              <span class="material-symbols-outlined fs-6 me-2">
                description
              </span>
              Descrizione
            </h6>
            <p
              class="mb-0 lh-base"
              id="modalServiceDescription"
            ></p>
          </div>
          <div
            id="modalObjectivesSection"
            class="mb-4"
            style="display: none"
          >
            <h6 class="fw-semibold d-flex align-items-center mb-2 text-muted">
              <span class="material-symbols-outlined fs-6 me-2">flag</span>
              Obiettivi Principali
            </h6>
            <div
              class="mb-0 lh-base"
              id="modalServiceObjectives"
            ></div>
          </div>
          <div
            id="modalModalitiesSection"
            class="mb-4"
            style="display: none"
          >
            <h6 class="fw-semibold d-flex align-items-center mb-2 text-muted">
              <span class="material-symbols-outlined fs-6 me-2">settings</span>
              Modalità di Erogazione
            </h6>
            <div
              class="mb-0 lh-base"
              id="modalServiceModalities"
            ></div>
          </div>
        </div>
        <div class="modal-footer border-0 bg-light p-3">
          <a
            href="#"
            id="modalContactButton"
            class="btn btn-primary rounded-pill flex-grow-1 flex-md-grow-0 focus-ring"
          >
            <span class="material-symbols-outlined fs-6 me-1 align-middle">
              send
            </span>
            Richiedi Informazioni
          </a>
          <button
            type="button"
            class="btn btn-outline-secondary rounded-pill flex-grow-1 flex-md-grow-0 focus-ring"
            data-bs-dismiss="modal"
          >
            <span class="material-symbols-outlined fs-6 me-1 align-middle">
              close
            </span>
            Chiudi
          </button>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
