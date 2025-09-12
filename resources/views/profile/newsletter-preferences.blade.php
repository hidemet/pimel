<x-app-layout>
  @section('title', 'Preferenze Newsletter - PIMEL')

  <x-slot name="pageHeader">
    <x-layout.page-header
      title="Preferenze Newsletter"
      subtitle="Gestisci gli argomenti che desideri seguire o annulla la tua iscrizione."
    />
  </x-slot>

  <div class="container py-4 py-md-5">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-7">
        @if (session('success'))
          <div
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
          </div>
        @endif

        <div class="card shadow-sm">
          <div class="card-body p-4 p-md-5">
            @if ($subscription)
              <p class="small text-muted mb-4">
                Sei attualmente iscritto alla newsletter con l'email:
                <strong>{{ $subscription->email }}</strong>
                .
              </p>

              <form
                method="POST"
                action="{{ route('profile.newsletter.update') }}"
                id="newsletterPreferencesForm"
              >
                @csrf
                @method('PATCH')

                <fieldset class="mb-4">
                  <legend class="form-label fw-semibold fs-6 mb-3">
                    Scegli le rubriche da seguire:
                  </legend>

                  @if ($errors->has('rubriche_selezionate') || $errors->has('rubriche_selezionate.*'))
                    <div
                      class="alert alert-danger small py-2 mb-3"
                      role="alert"
                    >
                      <i class="bi bi-exclamation-triangle-fill me-2"></i>
                      @error('rubriche_selezionate')
                        {{ $message }}
                        <br />
                      @enderror

                      @error('rubriche_selezionate.*')
                        {{ $message }}
                      @enderror
                    </div>
                  @endif

                  @if ($allRubrics->isNotEmpty())
                    <div class="row g-3">
                      @foreach ($allRubrics as $rubric)
                        <div class="col-md-6">
                          <div class="form-check">
                            <input
                              class="form-check-input profile-rubric-checkbox"
                              type="checkbox"
                              id="profile_rubrica_{{ $rubric->id }}"
                              name="rubriche_selezionate[]"
                              value="{{ $rubric->id }}"
                              {{ is_array(old('rubriche_selezionate', $subscribedRubricIds)) && in_array($rubric->id, old('rubriche_selezionate', $subscribedRubricIds)) ? 'checked' : '' }}
                            />
                            <label
                              class="form-check-label"
                              for="profile_rubrica_{{ $rubric->id }}"
                            >
                              {{ $rubric->name }}
                            </label>
                          </div>
                        </div>
                      @endforeach
                    </div>
                  @else
                    <p class="text-muted">
                      Al momento non ci sono rubriche disponibili.
                    </p>
                  @endif
                </fieldset>

                <div class="d-flex flex-column flex-sm-row gap-2">
                  <button
                    type="submit"
                    class="btn btn-primary"
                  >
                    <span
                      class="material-symbols-outlined fs-6 align-middle me-1"
                    >
                      save
                    </span>
                    Salva Preferenze
                  </button>
                  <a
                    href="{{ route('profile.edit') }}"
                    class="btn btn-outline-secondary"
                  >
                    Annulla
                  </a>
                </div>
              </form>

              <hr class="my-4 my-md-5" />

              <div>
                <h3 class="h6 fw-semibold mb-2">Annulla Iscrizione</h3>
                <p class="small text-muted mb-3">
                  Se non desideri più ricevere la newsletter, puoi annullare la
                  tua iscrizione qui.
                </p>
                <form
                  method="POST"
                  action="{{ route('profile.newsletter.destroy') }}"
                  onsubmit="return confirm('Sei sicuro di voler annullare la tua iscrizione alla newsletter? Questa azione è reversibile.');"
                >
                  @csrf
                  @method('DELETE')
                  <button
                    type="submit"
                    class="btn btn-danger btn-sm"
                  >
                    <span
                      class="material-symbols-outlined fs-6 align-middle me-1"
                    >
                      unsubscribe
                    </span>
                    Annulla Iscrizione alla Newsletter
                  </button>
                </form>
              </div>
            @else
              <div
                class="alert alert-info d-flex align-items-center"
                role="alert"
              >
                <span class="material-symbols-outlined fs-4 me-3">info</span>
                <div>
                  Non risulti attualmente iscritto alla newsletter con
                  l'indirizzo email
                  <strong>{{ Auth::user()->email }}</strong>
                  .
                  <br />
                  <a
                    href="{{ route('newsletter.form') }}"
                    class="alert-link fw-medium"
                  >
                    Desideri iscriverti ora?
                  </a>
                </div>
              </div>
              <div class="mt-4">
                <a
                  href="{{ route('profile.edit') }}"
                  class="btn btn-outline-secondary"
                >
                  <span
                    class="material-symbols-outlined fs-6 align-middle me-1"
                  >
                    arrow_back
                  </span>
                  Torna al Profilo
                </a>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
