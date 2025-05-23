{{-- resources/views/servizi/index.blade.php --}}
<x-app-layout>
    @section('title', 'I Nostri Servizi - Pedagogia in Movimento')
    @section('description',
        'Consulenza pedagogica e formazione su misura per genitori, professionisti dell\'educazione
        e istituzioni scolastiche. Trova il percorso più adatto alle tue esigenze.')
        <div class="container page-content-container">

            {{-- Sezione Hero migliorata --}}
            {{-- Page Header migliorato --}}
            <x-layout.page-header title="I Nostri Servizi"
                subtitle="Ogni percorso educativo è unico. I nostri servizi sono progettati per accompagnarti
                            con professionalità e cura in ogni fase della crescita e dello sviluppo.">

                {{-- Breadcrumb per orientamento --}}
                <nav aria-label="breadcrumb" class="mt-3">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                <span class="material-symbols-outlined fs-6 align-middle me-1">home</span>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Servizi</li>
                    </ol>
                </nav>
            </x-layout.page-header>

            {{-- Sezioni servizi per target --}}
            @php
                $targetColumns = [
                    'genitori' => [
                        'title' => 'Per Genitori',
                        'icon' => 'family_restroom',
                    ],
                    'professionisti' => [
                        'title' => 'Per Professionisti',
                        'icon' => 'work',
                    ],
                    'scuole' => [
                        'title' => 'Per le Scuole',
                        'icon' => 'school',
                    ],
                ];
            @endphp

            <div class="row g-4 mb-5">
                @foreach ($targetColumns as $targetSlug => $columnData)
                    @if (isset($servicesByTarget[$targetSlug]) && !$servicesByTarget[$targetSlug]->isEmpty())
                        <div class="col-lg-4 col-md-6">
                            <div class="card service-category-card h-100 border-0 shadow-sm rounded-4" role="region"
                                aria-labelledby="category-{{ $targetSlug }}-title">

                                {{-- Header della categoria --}}
                                <div class="card-header border-0 text-center">
                                    <div class="service-icon-wrapper mb-3">
                                        <div
                                            class="rounded-circle p-3 d-inline-flex align-items-center justify-content-center">
                                            <span class="material-symbols-outlined fs-1">{{ $columnData['icon'] }}</span>
                                        </div>
                                    </div>
                                    <h2 id="category-{{ $targetSlug }}-title" class="h4 fw-bold mb-2">
                                        {{ $columnData['title'] }}
                                    </h2>
                                </div>

                                {{-- Lista servizi migliorata --}}
                                <div class="card-body d-flex flex-column p-3">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge rounded-pill px-3">
                                                {{ $servicesByTarget[$targetSlug]->count() }}
                                                {{ $servicesByTarget[$targetSlug]->count() === 1 ? 'servizio' : 'servizi' }}
                                            </span>
                                        </div>
                                    </div>

                                    <ul class="list-unstyled mb-0 flex-grow-1" role="list">
                                        @foreach ($servicesByTarget[$targetSlug] as $service)
                                            <li class="service-item mb-3" role="listitem">
                                                <div class="d-flex align-items-start">
                                                    <span class="material-symbols-outlined fs-6 me-2 mt-1 flex-shrink-0">
                                                        arrow_forward_ios
                                                    </span>
                                                    <div class="flex-grow-1">
                                                        <button type="button"
                                                            class="btn btn-link p-0 text-start fw-semibold text-body text-decoration-none service-link"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#serviceModal{{ $service->id }}"
                                                            aria-describedby="service-{{ $service->id }}-preview">
                                                            {{ $service->name }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Sezione "Altri Servizi" migliorata --}}
            @if (isset($servicesByTarget['altri']) && !$servicesByTarget['altri']->isEmpty())
                <section class="mt-5 pt-4" id="servizi-altri" aria-labelledby="altri-servizi-title">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="text-center mb-4">
                                <span
                                    class="material-symbols-outlined display-3 text-warning mb-3 d-block">auto_awesome</span>
                                <h2 id="altri-servizi-title" class="h3 fw-bold text-warning mb-2">
                                    Servizi Speciali e Percorsi Personalizzati
                                </h2>
                                <p class="text-muted lead">
                                    Soluzioni uniche per esigenze specifiche
                                </p>
                            </div>

                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-header bg-warning bg-opacity-10 border-0 text-center py-3">
                                    <h3
                                        class="h5 fw-semibold mb-0 text-warning d-flex align-items-center justify-content-center">
                                        <span class="material-symbols-outlined fs-5 me-2">star</span>
                                        Proposte Speciali
                                    </h3>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        @foreach ($servicesByTarget['altri'] as $index => $service)
                                            <div class="col-md-6 mb-3">
                                                <div class="d-flex align-items-start p-3 bg-light rounded-3 h-100">
                                                    <span
                                                        class="material-symbols-outlined fs-5 text-warning me-3 mt-1 flex-shrink-0">
                                                        diamond
                                                    </span>
                                                    <div class="flex-grow-1">
                                                        <button type="button"
                                                            class="btn btn-link p-0 text-start fw-semibold text-body text-decoration-none"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#serviceModal{{ $service->id }}">
                                                            {{ $service->name }}
                                                        </button>
                                                        @if ($service->description)
                                                            <p class="small text-muted mb-0 mt-1">
                                                                {{ Str::limit(strip_tags($service->description), 60) }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            {{-- Sezione finale con CTA principale --}}
            <section class="mt-5 pt-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <div class="card border-0 bg-primary bg-opacity-5 rounded-4 p-4">
                            <div class="card-body">
                                <span
                                    class="material-symbols-outlined display-4 text-primary mb-3 d-block">support_agent</span>
                                <h2 class="h3 fw-bold mb-3">Hai bisogno di aiuto nella scelta?</h2>
                                <p class="text-muted mb-4 lead">
                                    Non sei sicuro quale servizio sia più adatto a te?
                                    Contattaci per una consulenza gratuita e personalizzata.
                                </p>
                                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                                    <a href="{{ route('contatti.form') }}"
                                        class="btn btn-primary btn-lg rounded-pill px-4">
                                        <span class="material-symbols-outlined fs-6 me-2">contact_support</span>
                                        Richiedi consulenza gratuita
                                    </a>
                                    <a href="tel:+393123456789" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                                        <span class="material-symbols-outlined fs-6 me-2">call</span>
                                        Chiamaci ora
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- Modal migliorati per i servizi --}}
        @if (isset($allActiveServices))
            @foreach ($allActiveServices as $service)
                <div class="modal fade" id="serviceModal{{ $service->id }}" tabindex="-1"
                    aria-labelledby="serviceModalLabel{{ $service->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-primary bg-opacity-10 border-0">
                                <div class="d-flex align-items-center">
                                    <span class="material-symbols-outlined fs-4 text-primary me-2">info</span>
                                    <h5 class="modal-title fw-bold text-primary mb-0"
                                        id="serviceModalLabel{{ $service->id }}">
                                        {{ $service->name }}
                                    </h5>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Chiudi dettagli servizio"></button>
                            </div>

                            <div class="modal-body p-4">
                                {{-- Descrizione --}}
                                @if ($service->description)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold text-primary d-flex align-items-center mb-2">
                                            <span class="material-symbols-outlined fs-6 me-2">description</span>
                                            Descrizione
                                        </h6>
                                        <div class="bg-light rounded-3 p-3">
                                            <p class="mb-0 lh-base">{!! nl2br(e($service->description)) !!}</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Target audience --}}
                                @if ($service->target_audience)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold text-success d-flex align-items-center mb-2">
                                            <span class="material-symbols-outlined fs-6 me-2">group</span>
                                            A chi si rivolge
                                        </h6>
                                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                            <p class="mb-0 lh-base">{!! nl2br(e($service->target_audience)) !!}</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Obiettivi --}}
                                @if ($service->objectives)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold text-info d-flex align-items-center mb-2">
                                            <span class="material-symbols-outlined fs-6 me-2">flag</span>
                                            Obiettivi principali
                                        </h6>
                                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                            <p class="mb-0 lh-base">{!! nl2br(e($service->objectives)) !!}</p>
                                        </div>
                                    </div>
                                @endif

                                {{-- Modalità --}}
                                @if ($service->modalities)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold text-warning d-flex align-items-center mb-2">
                                            <span class="material-symbols-outlined fs-6 me-2">settings</span>
                                            Modalità di erogazione
                                        </h6>
                                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                            <p class="mb-0 lh-base">{!! nl2br(e($service->modalities)) !!}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="modal-footer border-0 bg-light p-4">
                                <div class="d-flex flex-column flex-md-row gap-2 w-100">
                                    <a href="{{ route('contatti.form', ['service_of_interest' => $service->name]) }}"
                                        class="btn btn-primary rounded-pill flex-fill">
                                        <span class="material-symbols-outlined fs-6 me-2">send</span>
                                        Richiedi informazioni
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                                        data-bs-dismiss="modal">
                                        <span class="material-symbols-outlined fs-6 me-2">close</span>
                                        Chiudi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @push('styles')
            <style>
                /* Miglioramenti UX/UI per le card servizi */
                .service-category-card {
                    transition: all 0.3s ease;
                    border: 1px solid transparent;
                }

                .service-category-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
                    border-color: rgba(var(--bs-primary-rgb), 0.2);
                }

                .service-icon-wrapper .material-symbols-outlined {
                    transition: transform 0.3s ease;
                }

                .service-category-card:hover .service-icon-wrapper .material-symbols-outlined {
                    transform: scale(1.1);
                }

                .service-item {
                    transition: all 0.2s ease;
                    padding: 0.5rem;
                    margin: -0.5rem;
                    border-radius: 0.5rem;
                }

                .service-item:hover {
                    background-color: rgba(0, 0, 0, 0.02);
                }

                .service-link {
                    transition: color 0.2s ease;
                }

                .service-link:hover {
                    color: var(--bs-primary) !important;
                }

                /* Miglioramenti responsive */
                @media (max-width: 768px) {
                    .service-category-card:hover {
                        transform: none;
                    }
                }

                /* Focus states migliorati per accessibilità */
                .service-link:focus-visible,
                .btn:focus-visible {
                    outline: 2px solid var(--bs-primary);
                    outline-offset: 2px;
                }
            </style>
        @endpush

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Miglioramento accessibilità per i modal
                    const serviceModals = document.querySelectorAll('[id^="serviceModal"]');

                    serviceModals.forEach(modal => {
                        modal.addEventListener('shown.bs.modal', function() {
                            // Focus sul titolo quando il modal si apre
                            const title = this.querySelector('.modal-title');
                            if (title) {
                                title.focus();
                            }
                        });
                    });

                    // Smooth scroll per link interni
                    const internalLinks = document.querySelectorAll('a[href^="#"]');
                    internalLinks.forEach(link => {
                        link.addEventListener('click', function(e) {
                            const targetId = this.getAttribute('href').substring(1);
                            const targetElement = document.getElementById(targetId);

                            if (targetElement) {
                                e.preventDefault();
                                targetElement.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        });
                    });

                    // Preload delle immagini per migliorare le performance
                    const lazyElements = document.querySelectorAll('[loading="lazy"]');
                    if ('IntersectionObserver' in window) {
                        const lazyObserver = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    const img = entry.target;
                                    img.src = img.dataset.src || img.src;
                                    lazyObserver.unobserve(img);
                                }
                            });
                        });

                        lazyElements.forEach(img => lazyObserver.observe(img));
                    }
                });
            </script>
        @endpush
        </div>
    </x-app-layout>
