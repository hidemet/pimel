{{-- resources/views/servizi/index.blade.php --}}
<x-app-layout>
    <x-slot name="pageHeader">
        <x-layout.page-header title="I Nostri Servizi" bgClass="bg-default">

        </x-layout.page-header>
    </x-slot>

    <div class="container py-4 py-md-5"> {{-- Aggiunto py-4 py-md-5 per spaziatura --}}

        @if ($targetCategories->isEmpty() && $uncategorizedServices->isEmpty())
            <div class="text-center py-5">
                <span class="material-symbols-outlined display-1 text-muted mb-3">sentiment_dissatisfied</span>
                <h2 class="h4">Nessun servizio disponibile al momento.</h2>
                <p class="text-muted">Torna a trovarci presto per scoprire le nostre offerte.</p>
            </div>
        @else
            <div class="row g-4 g-xl-5"> {{-- Aumentato il gutter su schermi grandi --}}
                @foreach ($targetCategories as $category)
                    @if ($category->services->isNotEmpty())
                        {{-- Mostra la colonna solo se ci sono servizi in questa categoria --}}
                        <div class="col-lg-4 col-md-6 d-flex"> {{-- Aggiunto d-flex per far sì che la card occupi tutta l'altezza --}}
                            <section class="card shadow-sm w-100 flex-fill"> {{-- Aggiunto w-100 e flex-fill --}}
                                <div class="card-header text-center py-3 border-0">
                                    <div class="mb-2">
                                        @if ($category->icon_class)
                                            <span
                                                class="material-symbols-outlined fs-1 text-dark">{{ $category->icon_class }}</span>
                                        @else
                                            <span class="material-symbols-outlined fs-1 text-dark">category</span>
                                            {{-- Icona di fallback --}}
                                        @endif
                                    </div>
                                    <h2 class="h5 fw-semibold mb-0 text-dark">
                                        {{ $category->name }}
                                    </h2>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    @if ($category->description)
                                        <p class="small text-muted mb-3">{{ $category->description }}</p>
                                    @endif
                                    <ul class="list-unstyled mb-0 mt-auto"> {{-- mt-auto per spingere la lista in basso se c'è descrizione --}}
                                        @foreach ($category->services as $service)
                                            <li class="mb-2">
                                                <button type="button"
                                                    class="btn btn-link p-0 text-start fw-medium text-body text-decoration-none focus-ring w-100 service-modal-trigger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#serviceModal{{ $service->id }}"
                                                    data-service-name="{{ $service->name }}"
                                                    data-service-description="{{ $service->description }}"
                                                    data-service-target-audience="{{ $service->target_audience }}"
                                                    data-service-objectives="{{ $service->objectives }}"
                                                    data-service-modalities="{{ $service->modalities }}"
                                                    data-service-contact-url="{{ route('contatti.form', ['service_of_interest' => $service->name]) }}">
                                                    <i
                                                        class="bi bi-check-circle text-primary me-2"></i>{{ $service->name }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </section>
                        </div>
                    @endif
                @endforeach

                {{-- Sezione per servizi non categorizzati --}}
                @if ($uncategorizedServices->isNotEmpty())
                    <div class="col-lg-4 col-md-6 d-flex">
                        <section class="card shadow-sm w-100 flex-fill">
                            <div class="card-header bg-secondary bg-opacity-10 text-center py-3 border-0">
                                <div class="mb-2">
                                    <span class="material-symbols-outlined fs-1 text-secondary">pending</span>
                                    {{-- Icona per non categorizzati --}}
                                </div>
                                <h2 class="h5 fw-semibold mb-0 text-secondary">
                                    Altri Servizi
                                </h2>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <ul class="list-unstyled mb-0 mt-auto">
                                    @foreach ($uncategorizedServices as $service)
                                        <li class="mb-2">
                                            <button type="button"
                                                class="btn btn-link p-0 text-start fw-medium text-body text-decoration-none focus-ring w-100 service-modal-trigger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#serviceModal{{ $service->id }}"
                                                data-service-name="{{ $service->name }}"
                                                data-service-description="{{ $service->description }}"
                                                data-service-target-audience="{{ $service->target_audience }}"
                                                data-service-objectives="{{ $service->objectives }}"
                                                data-service-modalities="{{ $service->modalities }}"
                                                data-service-contact-url="{{ route('contatti.form', ['service_of_interest' => $service->name]) }}">
                                                <i
                                                    class="bi bi-check-circle text-secondary me-2"></i>{{ $service->name }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </section>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Modal unico per i dettagli del servizio --}}
    <div class="modal fade" id="serviceDetailModal" tabindex="-1" aria-labelledby="serviceDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-0">
                    <div class="d-flex align-items-center">
                        <span class="material-symbols-outlined fs-4 text-primary me-2" id="modalIcon">info</span>
                        <h5 class="modal-title fw-bold text-primary mb-0" id="serviceDetailModalLabel">
                            Dettagli Servizio
                        </h5>
                    </div>
                    <button type="button" class="btn-close focus-ring" data-bs-dismiss="modal"
                        aria-label="Chiudi"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Contenuto caricato da JS --}}
                    <div id="modalServiceName" class="h4 fw-bold mb-3"></div>

                    <div id="modalDescriptionSection" class="mb-4" style="display:none;">
                        <h6 class="fw-semibold d-flex align-items-center mb-2 text-muted">
                            <span class="material-symbols-outlined fs-6 me-2">description</span>Descrizione
                        </h6>
                        <p class="mb-0 lh-base" id="modalServiceDescription"></p>
                    </div>

                    <div id="modalTargetAudienceSection" class="mb-4" style="display:none;">
                        <h6 class="fw-semibold d-flex align-items-center mb-2 text-muted">
                            <span class="material-symbols-outlined fs-6 me-2">group</span>A chi si rivolge
                        </h6>
                        <p class="mb-0 lh-base" id="modalServiceTargetAudience"></p>
                    </div>

                    <div id="modalObjectivesSection" class="mb-4" style="display:none;">
                        <h6 class="fw-semibold d-flex align-items-center mb-2 text-muted">
                            <span class="material-symbols-outlined fs-6 me-2">flag</span>Obiettivi principali
                        </h6>
                        <div class="mb-0 lh-base" id="modalServiceObjectives"></div> {{-- Usare div per nl2br --}}
                    </div>

                    <div id="modalModalitiesSection" class="mb-4" style="display:none;">
                        <h6 class="fw-semibold d-flex align-items-center mb-2 text-muted">
                            <span class="material-symbols-outlined fs-6 me-2">settings</span>Modalità di erogazione
                        </h6>
                        <div class="mb-0 lh-base" id="modalServiceModalities"></div> {{-- Usare div per nl2br --}}
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light p-3">
                    <a href="#" id="modalContactButton"
                        class="btn btn-primary rounded-pill flex-grow-1 flex-md-grow-0 focus-ring">
                        <span class="material-symbols-outlined fs-6 me-1 align-middle">send</span>
                        Richiedi informazioni
                    </a>
                    <button type="button"
                        class="btn btn-outline-secondary rounded-pill flex-grow-1 flex-md-grow-0 focus-ring"
                        data-bs-dismiss="modal">
                        <span class="material-symbols-outlined fs-6 me-1 align-middle">close</span>
                        Chiudi
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal unico per i dettagli del servizio (rimane qui o in fondo al body se preferisci) --}}
    <div class="modal fade" id="serviceDetailModal" {{-- ... --}}>
        {{-- ... contenuto modal ... --}}
    </div>


    {{-- NUOVA SEZIONE CTA A TUTTA LARGHEZZA (spostata nello slot dedicato) --}}
    <x-slot name="afterMainFullWidthSection">
        <div class="full-width-strip py-5"> {{-- Il colore desiderato ($bg-form-color) --}}
            <div class="container py-5"> {{-- Container interno per centrare il contenuto --}}
                <div class="row justify-content-center">
                    <div class="col-lg-7 text-start">
                        <h1 class=" display-6 fw-semibold text-dark mb-4">
                            Hai bisogno di aiuto nella scelta?</h1>
                        <p class="text-muted mb-4 lead fs-6">
                            Non sei sicuro quale servizio sia più adatto a te?
                            Contattaci per una consulenza gratuita e personalizzata.
                        </p>
                        <div class="text-start"> <a href="{{ route('contatti.form') }}"
                                class="btn btn-dark btn-lg focus-ring px-4 me-3">
                                Richiedi consulenza
                            </a>
                            {{-- Potresti aggiungere un altro bottone se serve, es. "Vedi tutti i servizi" --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const serviceDetailModal = new bootstrap.Modal(document.getElementById('serviceDetailModal'));
                const modalTriggers = document.querySelectorAll('.service-modal-trigger');

                const modalServiceNameEl = document.getElementById('modalServiceName');
                const modalDescriptionEl = document.getElementById('modalServiceDescription');
                const modalTargetAudienceEl = document.getElementById('modalServiceTargetAudience');
                const modalObjectivesEl = document.getElementById('modalServiceObjectives');
                const modalModalitiesEl = document.getElementById('modalServiceModalities');
                const modalContactButton = document.getElementById('modalContactButton');

                // Sezioni opzionali
                const descriptionSection = document.getElementById('modalDescriptionSection');
                const targetAudienceSection = document.getElementById('modalTargetAudienceSection');
                const objectivesSection = document.getElementById('modalObjectivesSection');
                const modalitiesSection = document.getElementById('modalModalitiesSection');

                modalTriggers.forEach(button => {
                    button.addEventListener('click', function() {
                        const serviceName = this.dataset.serviceName;
                        const description = this.dataset.serviceDescription;
                        const targetAudience = this.dataset.serviceTargetAudience;
                        const objectives = this.dataset.serviceObjectives;
                        const modalities = this.dataset.serviceModalities;
                        const contactUrl = this.dataset.serviceContactUrl;

                        modalServiceNameEl.textContent = serviceName || 'Dettagli Servizio';

                        function nl2br(str) {
                            if (typeof str === 'undefined' || str === null) {
                                return '';
                            }
                            return str.replace(/\r\n|\r|\n/g, '<br>');
                        }

                        // Popola e mostra/nascondi sezioni
                        if (description && description.trim() !== '') {
                            modalDescriptionEl.innerHTML = nl2br(description);
                            descriptionSection.style.display = 'block';
                        } else {
                            descriptionSection.style.display = 'none';
                        }

                        if (targetAudience && targetAudience.trim() !== '') {
                            modalTargetAudienceEl.innerHTML = nl2br(targetAudience);
                            targetAudienceSection.style.display = 'block';
                        } else {
                            targetAudienceSection.style.display = 'none';
                        }

                        if (objectives && objectives.trim() !== '') {
                            modalObjectivesEl.innerHTML = nl2br(objectives);
                            objectivesSection.style.display = 'block';
                        } else {
                            objectivesSection.style.display = 'none';
                        }

                        if (modalities && modalities.trim() !== '') {
                            modalModalitiesEl.innerHTML = nl2br(modalities);
                            modalitiesSection.style.display = 'block';
                        } else {
                            modalitiesSection.style.display = 'none';
                        }

                        if (contactUrl) {
                            modalContactButton.href = contactUrl;
                            modalContactButton.style.display = ''; // Assicura che sia visibile
                        } else {
                            modalContactButton.style.display = 'none'; // Nascondi se non c'è URL
                        }

                        serviceDetailModal.show();
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
