<x-app-layout>

    @section('title', 'Iscriviti alla Newsletter - Pedagogia in Movimento')

    <x-slot name="pageHeader">
        <x-layout.page-header title="Resta Aggiornato con Pedagogia in Movimento">
        </x-layout.page-header>
    </x-slot>

    <main class="container py-4 py-md-3 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <section class="bg-form-section rounded-5 p-5 p-md-5">
                    <header class="mb-4">
                        <h1 id="newsletter-form-title" class="h4 text-dark d-flex align-items-center">
                            Iscriviti alla Newsletter
                        </h1>
                    </header>

                    {{-- Alert di successo --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4"
                            role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Alert di errore --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start mb-4"
                            role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                            <div>
                                <h2 class="alert-heading fw-semibold h6">Attenzione!</h2>
                                <p class="mb-0 small">Controlla i campi del modulo, si sono verificati degli errori.</p>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('newsletter.subscribe') }}" novalidate
                        class="needs-validation">
                        @csrf

                        <p id="newsletter-form-description" class="visually-hidden">
                            Compila il modulo per iscriverti alla newsletter di Pedagogia in Movimento
                        </p>

                        {{-- Sezione dati personali --}}
                        <fieldset class="mb-4">
                            <legend class="visually-hidden">Dati personali</legend>

                            {{-- Email --}}
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="newsletterEmail" name="email" value="{{ old('email') }}"
                                    placeholder="Indirizzo Email" autocomplete="email">
                                <label for="newsletterEmail">Indirizzo Email <abbr title="Campo obbligatorio"
                                        class="text-danger">*</abbr></label>
                                <div id="email-help" class="form-text visually-hidden">Inserisci un indirizzo email
                                    valido</div>
                                @error('email')
                                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </fieldset>

                        {{-- Sezione preferenze --}}
                        @if (isset($rubrics) && $rubrics->count())
                            <fieldset class="mb-4">
                                <legend class="form-label fw-semibold fs-6 mb-3">
                                    Argomenti di interesse <span class="text-muted">(opzionale)</span>
                                </legend>

                                @error('rubriche_selezionate')
                                    <div class="alert alert-warning small py-2 mb-3" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                                @error('rubriche_selezionate.*')
                                    <div class="alert alert-warning small py-2 mb-3" role="alert">
                                        <i class="bi bi-info-circle me-2"></i>{{ $message }}
                                    </div>
                                @enderror

                                <div class="border rounded-3 p-3 border-0" role="group">
                                    <div id="rubrics-group-label" class="visually-hidden">Seleziona gli argomenti di tuo
                                        interesse</div>
                                    <div class="row g-3">
                                        @foreach ($rubrics as $rubric)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="rubrica_{{ $rubric->id }}" name="rubriche_selezionate[]"
                                                        value="{{ $rubric->id }}"
                                                        {{ (is_array(old('rubriche_selezionate')) && in_array($rubric->id, old('rubriche_selezionate'))) || old('select_all_rubriche') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="rubrica_{{ $rubric->id }}">
                                                        {{ $rubric->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </fieldset>
                        @endif

                        {{-- Azione principale --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-lg mt-3">
                                Iscriviti Ora
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </main>
</x-app-layout>
