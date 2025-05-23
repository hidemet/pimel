<x-app-layout>
    @section('title', 'Iscriviti alla Newsletter - Pedagogia in Movimento')

    <x-layout.page-header title="Resta Aggiornato con Pedagogia in Movimento"
        subtitle="Iscriviti per ricevere tutti i contenuti e le novità di Pedagogia in Movimento." />

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7">
                    <div class="form-group bg-primary-subtle shadow border-1 rounded-4">
                        <div class="p-4 p-md-5">
                            {{-- Alert di successo --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4"
                                    role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <div>{{ session('success') }}</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- Alert di errore --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start mb-4"
                                    role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                                    <div>
                                        <strong>Attenzione!</strong> Controlla i campi del modulo.
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('newsletter.subscribe') }}" novalidate
                                class="needs-validation">
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
                                        id="contactEmail" name="email" value="{{ old('email') }}" placeholder="email"
                                        required aria-describedby="emailHelp">
                                    <label for="contactEmail">
                                        </i>Email <span class="text-danger">*</span>
                                </div>


                                {{-- Rubriche --}}
                                <fieldset class="mb-4">
                                    <legend class="form-label fw-semibold fs-6">
                                        <i class="bi bi-tags-fill me-1"></i>Argomenti di interesse
                                    </legend>

                                    @error('rubriche_selezionate')
                                        <div class="alert alert-warning alert-sm d-flex align-items-center py-2 mb-3"
                                            role="alert">
                                            <i class="bi bi-info-circle me-2"></i>{{ $message }}
                                        </div>
                                    @enderror

                                    @if (isset($rubrics) && $rubrics->count())
                                        <div class="border rounded-3 p-3 bg-primary-subtle">
                                            <div class="row g-3">
                                                @foreach ($rubrics as $rubric)
                                                    <div class="col-md-6">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="rubrica_{{ $rubric->id }}"
                                                                name="rubriche_selezionate[]"
                                                                value="{{ $rubric->id }}"
                                                                {{ (is_array(old('rubriche_selezionate')) && in_array($rubric->id, old('rubriche_selezionate'))) || old('select_all_rubriche') ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="rubrica_{{ $rubric->id }}">
                                                                {{ $rubric->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </fieldset>

                                {{-- Privacy --}}
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input @error('privacy') is-invalid @enderror"
                                            type="checkbox" id="privacyNewsletter" name="privacy" value="1"
                                            {{ old('privacy') ? 'checked' : '' }} required
                                            aria-describedby="privacyHelp">
                                        <label class="form-check-label" for="privacyNewsletter">
                                            Accetto l'<a href="#"
                                                class="link-primary text-decoration-none">informativa sulla privacy</a>
                                            per ricevere la newsletter. <span class="text-danger">*</span>
                                        </label>
                                        <div id="privacyHelp" class="form-text">
                                            La tua privacy è importante per noi. Puoi annullare l'iscrizione in
                                            qualsiasi momento.
                                        </div>
                                        @error('privacy')
                                            <div class="invalid-feedback d-flex align-items-center">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Pulsante conferma --}}
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-ctc btn-lg rounded-pill shadow-sm">
                                        <i class="bi bi-envelope-plus me-2"></i>
                                        Iscriviti alla Newsletter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
