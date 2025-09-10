<x-app-layout>
  @section('title', 'Contattami - Pedagogia in Movimento')

  <x-layout.page-header title="Mettiamoci in Contatto" />

  <main class="container-fluid px-3 px-md-4 mt-4 mt-md-5 mb-5">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10 col-xl-8">
        <div class="row g-4">
          <!-- Sezione Modulo -->
          <section class="col-lg-7">
            <article class="bg-form-section rounded-5 p-5 p-md-5">
              {{-- Messaggi di sessione --}}
              @if (session('success'))
                <aside
                  class="alert alert-success alert-dismissible fade show"
                  role="alert"
                >
                  <i class="bi bi-check-circle-fill me-2"></i>
                  {{ session('success') }}
                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                  ></button>
                </aside>
              @endif

              {{-- Errori generali --}}
              @if (

                $errors->any() &&
                ! $errors->has('name') &&
                ! $errors->has('email') &&
                ! $errors->has('message') &&
                ! $errors->has('service_of_interest') &&
                ! $errors->has('subject')              )
                <aside
                  class="alert alert-danger alert-dismissible fade show"
                  role="alert"
                >
                  <p class="mb-2 fw-semibold">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Attenzione! Si sono verificati degli errori:
                  </p>
                  <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                  <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                    aria-label="Close"
                  ></button>
                </aside>
              @endif

              <header class="mb-2">
                <h1 class="h4 text-dark d-flex align-items-center">
                  Invia un messaggio
                </h1>
              </header>
              <div class="pt-3">
                <form
                  method="POST"
                  action="{{ route('contatti.store') }}"
                  id="contactForm"
                  class="needs-validation"
                  novalidate
                >
                  @csrf

                  {{-- Nome con Floating Label --}}
                  <fieldset class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control @error('name') is-invalid @enderror"
                      id="contactName"
                      name="name"
                      value="{{ old('name') }}"
                      placeholder="Il tuo nome completo"
                      required
                    />
                    <label for="contactName">
                      Nome Completo
                      <mark class="text-danger bg-transparent">*</mark>
                    </label>
                    @error('name')
                      <output class="invalid-feedback">{{ $message }}</output>
                    @enderror
                  </fieldset>

                  {{-- Email --}}
                  <fieldset class="form-floating mb-3">
                    <input
                      type="email"
                      class="form-control @error('email') is-invalid @enderror"
                      id="contactEmail"
                      name="email"
                      value="{{ old('email') }}"
                      placeholder="email"
                      required
                    />
                    <label for="contactEmail">
                      Email
                      <mark class="text-danger bg-transparent">*</mark>
                    </label>
                    @error('email')
                      <output class="invalid-feedback">{{ $message }}</output>
                    @enderror
                  </fieldset>

                  {{-- Servizio di Interesse --}}
                  <fieldset class="mb-3">
                    <label
                      for="contactService"
                      class="form-label fw-medium"
                    >
                      Servizio di Interesse
                    </label>
                    <select
                      class="form-select @error('service_of_interest') is-invalid @enderror"
                      id="contactService"
                      name="service_of_interest"
                    >
                      <option
                        value="{{ old('service_of_interest') == '' ? 'selected' : '' }}"
                      >
                        Seleziona un servizio (opzionale)...
                      </option>
                      @if (isset($services) && $services->count())
                        @foreach ($services as $serviceValue => $serviceName)
                          <option
                            value="{{ $serviceValue }}"
                            {{ old('service_of_interest') == $serviceValue ? 'selected' : '' }}
                          >
                            {{ $serviceName }}
                          </option>
                        @endforeach
                      @endif

                      <option
                        value="altro"
                        {{ old('service_of_interest') == 'altro' ? 'selected' : '' }}
                      >
                        Altro (specifica nell'oggetto o messaggio)
                      </option>
                    </select>
                    @error('service_of_interest')
                      <output class="invalid-feedback">{{ $message }}</output>
                    @enderror
                  </fieldset>

                  {{-- Oggetto --}}
                  <fieldset class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control @error('subject') is-invalid @enderror"
                      id="contactSubject"
                      name="subject"
                      value="{{ old('subject') }}"
                      placeholder="Oggetto del messaggio"
                    />
                    <label for="contactSubject">Oggetto</label>
                    @error('subject')
                      <output class="invalid-feedback">{{ $message }}</output>
                    @enderror
                  </fieldset>

                  {{-- Messaggio --}}
                  <fieldset class="form-floating mb-4">
                    <textarea
                      class="form-control @error('message') is-invalid @enderror"
                      id="contactMessage"
                      name="message"
                      placeholder="Scrivi qui il tuo messaggio..."
                      style="height: 120px"
                      required
                    >
{{ old('message') }}</textarea
                    >
                    <label for="contactMessage">
                      Messaggio
                      <mark class="text-danger bg-transparent">*</mark>
                    </label>
                    @error('message')
                      <output class="invalid-feedback">{{ $message }}</output>
                    @enderror
                  </fieldset>

                  {{-- Button --}}
                  <div class="d-grid">
                    <button
                      type="submit"
                      class="btn btn-dark btn-lg shadow-sm"
                      id="submitBtn"
                    >
                      <i class="bi bi-send me-2"></i>
                      <span class="button-text">Invia Messaggio</span>
                      <span
                        class="spinner-border spinner-border-sm me-2 d-none"
                        role="status"
                      ></span>
                      <span class="loading-text d-none">Invio in corso...</span>
                    </button>
                  </div>
                </form>
              </div>
            </article>
          </section>

          <!-- Sezione Informazioni -->
          <section class="col-lg-5 p-5">
            {{-- Email Card --}}
            <address class="card border-0 mb-3">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <h3 class="mb-1 fw-semibold h6">Email</h3>
                    <a
                      href="mailto:{{ config('mail.from.address', 'info@pimel.it') }}"
                      class="text-decoration-none"
                    >
                      {{ config('mail.from.address', 'info@pimel.it') }}
                    </a>
                  </div>
                </div>
              </div>
            </address>

            {{-- Social Media Card --}}
            <section class="card border-0">
              <div class="card-body">
                <h3 class="mb-3 fw-semibold d-flex align-items-center h6">
                  Seguimi sui Social
                </h3>
                <nav class="d-flex gap-2 flex-wrap">
                  <a
                    href="#"
                    class="btn btn-outline-primary rounded-circle p-2"
                    style="width: 2.5rem; height: 2.5rem"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Facebook"
                  >
                    <i class="bi bi-facebook"></i>
                  </a>
                  <a
                    href="#"
                    class="btn btn-outline-primary rounded-circle p-2"
                    style="width: 2.5rem; height: 2.5rem"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Instagram"
                  >
                    <i class="bi bi-instagram"></i>
                  </a>
                  <a
                    href="#"
                    class="btn btn-outline-primary rounded-circle p-2"
                    style="width: 2.5rem; height: 2.5rem"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="YouTube"
                  >
                    <i class="bi bi-youtube"></i>
                  </a>
                  <a
                    href="#"
                    class="btn btn-outline-primary rounded-circle p-2"
                    style="width: 2.5rem; height: 2.5rem"
                    data-bs-toggle="tooltip"
                    data-bs-placement="top"
                    title="Telegram"
                  >
                    <i class="bi bi-telegram"></i>
                  </a>
                </nav>
              </div>
            </section>
          </section>
        </div>
      </div>
    </div>
  </main>

  @push('scripts')
    <script>
      // Usa la sintassi standard di jQuery per eseguire il codice al caricamento del DOM.
      $(function () {
        const form = $('#contactForm');
        const submitBtn = $('#submitBtn');

        form.on('submit', function (event) {
          // Usa il metodo nativo del form per la validazione HTML5.
          if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          } else {
            // Se il form è valido, disabilita il pulsante e mostra lo stato di caricamento.
            submitBtn.prop('disabled', true);
            submitBtn.find('.button-text').addClass('d-none');
            submitBtn
              .find('.spinner-border, .loading-text')
              .removeClass('d-none');
          }

          // Aggiunge la classe di Bootstrap per mostrare i feedback di validazione.
          form.addClass('was-validated');
        });
      });
    </script>
  @endpush
</x-app-layout>
