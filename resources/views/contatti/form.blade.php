<x-app-layout>
    @section('title', 'Contattami - Pedagogia in Movimento')

    <x-layout.page-header title="Mettiamoci in Contatto"
        subtitle="Hai domande, proposte o vuoi conoscere meglio i servizi che offro? Compila il modulo o utilizza i contatti diretti. Sarò felice di risponderti!" />

    <div class="container-fluid px-3 px-md-4 mt-4 mt-md-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                {{-- Messaggi di sessione --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Errori generali --}}
                @if (
                    $errors->any() &&
                        !$errors->has('name') &&
                        !$errors->has('email') &&
                        !$errors->has('message') &&
                        !$errors->has('privacy') &&
                        !$errors->has('service_of_interest') &&
                        !$errors->has('subject'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p class="mb-2 fw-semibold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Attenzione! Si
                            sono verificati degli errori:</p>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row g-4">
                    <!-- Colonna Modulo -->
                    <div class="col-lg-7">
                        <div class=" border-0 bg-white shadow-sm rounded-4 p-4 pt-3">
                            <div class=" bg-transparent border-0 pb-3">
                                <h2 class="h4 mb-0 text-primary d-flex align-items-center">
                                    <i class="bi bi-envelope-paper me-2 fs-5"></i>
                                    Invia un messaggio
                                </h2>
                            </div>
                            <div class=" pt-3">
                                <form method="POST" action="{{ route('contatti.store') }}" id="contactForm" novalidate
                                    aria-labelledby="contact-form-title">
                                    @csrf

                                    {{-- Nome con Floating Label --}}
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="contactName" name="name" value="{{ old('name') }}"
                                            placeholder="Il tuo nome completo" required aria-describedby="nameHelp">
                                        <label for="contactName">
                                            </i>Nome Completo <span class="text-danger">*</span>
                                        </label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Email con Input Group --}}
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="contactEmail" name="email" value="{{ old('email') }}"
                                            placeholder="email" required aria-describedby="emailHelp">
                                        <label for="contactEmail">
                                            </i>Email <span class="text-danger">*</span>
                                    </div>

                                    {{-- Servizio di Interesse --}}
                                    <div class="mb-3">
                                        <label for="contactService" class="form-label fw-medium">
                                            </i>Servizio di Interesse
                                        </label>
                                        <select class="form-select @error('service_of_interest') is-invalid @enderror"
                                            id="contactService" name="service_of_interest"
                                            aria-describedby="serviceHelp">
                                            <option value="{{ old('service_of_interest') == '' ? 'selected' : '' }}">
                                                Seleziona un servizio (opzionale)...
                                            </option>
                                            @if (isset($services) && $services->count())
                                                @foreach ($services as $serviceValue => $serviceName)
                                                    <option value="{{ $serviceValue }}"
                                                        {{ old('service_of_interest') == $serviceValue ? 'selected' : '' }}>
                                                        {{ $serviceName }}
                                                    </option>
                                                @endforeach
                                            @endif
                                            <option value="altro"
                                                {{ old('service_of_interest') == 'altro' ? 'selected' : '' }}>
                                                Altro (specifica nell'oggetto o messaggio)
                                            </option>
                                        </select>
                                        @error('service_of_interest')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Oggetto con Floating Label --}}
                                    <div class="form-floating mb-3">
                                        <input type="text"
                                            class="form-control @error('subject') is-invalid @enderror"
                                            id="contactSubject" name="subject" value="{{ old('subject') }}"
                                            placeholder="Oggetto del messaggio">
                                        <label for="contactSubject">
                                            </i>Oggetto
                                        </label>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Messaggio con Floating Label --}}
                                    <div class="form-floating mb-4">
                                        <textarea class="form-control @error('message') is-invalid @enderror" id="contactMessage" name="message"
                                            placeholder="Scrivi qui il tuo messaggio..." style="height: 120px" required aria-describedby="messageHelp">{{ old('message') }}</textarea>
                                        <label for="contactMessage">
                                            </i>Messaggio <span class="text-danger">*</span>
                                        </label>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Privacy Policy migliorato --}}
                                    <div class="form-check mb-4">
                                        <input class="form-check-input @error('privacy') is-invalid @enderror"
                                            type="checkbox" value="accepted" id="privacyPolicy" name="privacy" required
                                            aria-required="true" {{ old('privacy') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="privacyPolicy">
                                            <i class="bi bi-shield-check me-1 text-success"></i>
                                            Ho letto e accetto l'<a href="#"
                                                class="text-decoration-none fw-medium" target="_blank">informativa
                                                sulla privacy</a>
                                            <span class="text-danger">*</span>
                                        </label>
                                        @error('privacy')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Button migliorato --}}
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm"
                                            id="submitBtn">
                                            <i class="bi bi-send me-2"></i>
                                            <span class="button-text">Invia Messaggio</span>
                                            <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                                aria-hidden="true"></span>
                                            <span class="loading-text d-none">Invio in corso...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Colonna Informazioni Migliorata -->
                    <div class="col-lg-5">
                        <div class="sticky-top" style="top: 2rem;">
                            <h2 class="h4 mb-3 d-flex align-items-center text-primary">
                                <i class="bi bi-telephone me-2"></i>Contatti Diretti
                            </h2>

                            {{-- Email Card --}}
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="bi bi-envelope-fill fs-5 text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-semibold">Email</h6>
                                            <a href="mailto:{{ config('mail.from.address', 'info@pimel.it') }}"
                                                class="text-decoration-none">
                                                {{ config('mail.from.address', 'info@pimel.it') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Social Media Card --}}
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body p-3">
                                    <h6 class="mb-3 fw-semibold d-flex align-items-center">
                                        <i class="bi bi-share me-2 text-primary"></i>Seguimi sui Social
                                    </h6>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="#" class="btn btn-outline-primary rounded-circle p-2"
                                            style="width: 2.5rem; height: 2.5rem;" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Facebook"
                                            aria-label="Seguici su Facebook">
                                            <i class="bi bi-facebook"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-primary rounded-circle p-2"
                                            style="width: 2.5rem; height: 2.5rem;" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Instagram"
                                            aria-label="Seguici su Instagram">
                                            <i class="bi bi-instagram"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-primary rounded-circle p-2"
                                            style="width: 2.5rem; height: 2.5rem;" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="YouTube" aria-label="Seguici su YouTube">
                                            <i class="bi bi-youtube"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-primary rounded-circle p-2"
                                            style="width: 2.5rem; height: 2.5rem;" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Telegram"
                                            aria-label="Contattaci su Telegram">
                                            <i class="bi bi-telegram"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                'use strict';

                // Validazione Bootstrap nativa
                const form = document.getElementById('contactForm');
                const submitBtn = document.getElementById('submitBtn');

                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        // Mostra stato loading
                        const buttonText = submitBtn.querySelector('.button-text');
                        const spinner = submitBtn.querySelector('.spinner-border');
                        const loadingText = submitBtn.querySelector('.loading-text');

                        buttonText.classList.add('d-none');
                        spinner.classList.remove('d-none');
                        loadingText.classList.remove('d-none');
                        submitBtn.disabled = true;
                    }

                    form.classList.add('was-validated');
                }, false);

                // I tooltip sono già inizializzati in app.js, non serve duplicare il codice
            })();
        </script>
    @endpush
</x-app-layout>
