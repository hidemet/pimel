{{-- resources/views/admin/newsletter/edit.blade.php --}}
<x-app-layout>
  @section('title', 'Modifica Iscrizione - Admin PIMEL')

  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <x-slot name="pageHeader">
    <x-layout.page-header
      title="Modifica Iscrizione"
      :subtitle="'Stai modificando l\'utente: ' . $newsletterSubscription->email"
    />
  </x-slot>

  <div class="container py-4">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <form
          action="{{ route('admin.newsletter-subscriptions.update', $newsletterSubscription) }}"
          method="POST"
        >
          @csrf
          @method('PUT')

          {{-- Campo Email --}}
          <div class="mb-4">
            <label
              for="email"
              class="form-label fw-semibold"
            >
              Indirizzo Email
            </label>
            <input
              type="email"
              name="email"
              id="email"
              class="form-control @error('email') is-invalid @enderror"
              value="{{ old('email', $newsletterSubscription->email) }}"
              required
            />
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- Preferenze Rubriche --}}
          <fieldset>
            <legend class="form-label fw-semibold">Preferenze Rubriche</legend>
            <div class="row">
              @forelse ($allRubrics as $rubric)
                <div class="col-md-6 col-lg-4">
                  <div class="form-check mb-2">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="rubriche_selezionate[]"
                      value="{{ $rubric->id }}"
                      id="rubric-{{ $rubric->id }}"
                      {{ in_array($rubric->id, old('rubriche_selezionate', $subscribedRubricIds)) ? 'checked' : '' }}
                    />
                    <label
                      class="form-check-label"
                      for="rubric-{{ $rubric->id }}"
                    >
                      {{ $rubric->name }}
                    </label>
                  </div>
                </div>
              @empty
                <p class="text-muted small">Nessuna rubrica disponibile.</p>
              @endforelse
            </div>
            @error('rubriche_selezionate.*')
              <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
          </fieldset>

          <div class="mt-4">
            <button
              type="submit"
              class="btn btn-primary"
            >
              Salva Modifiche
            </button>
            <a
              href="{{ route('admin.newsletter-subscriptions.index') }}"
              class="btn btn-outline-secondary"
            >
              Annulla
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
