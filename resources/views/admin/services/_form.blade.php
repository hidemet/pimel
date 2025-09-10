{{-- resources/views/admin/services/_form.blade.php --}}

@push('page-scripts')
  @vite('resources/js/admin-forms.js')
@endpush

@csrf
{{-- Token CSRF per la sicurezza del form --}}

{{--
  Questo form richiede le seguenti variabili passate dalla vista genitore (create.blade.php o edit.blade.php):
  - $service: istanza del modello App\Models\Service (nuova o esistente).
  - $targetCategories: collezione delle categorie target disponibili.
--}}

<div class="row">
  {{-- Colonna Principale (Campi di testo) --}}
  <div class="col-md-8">
    {{-- Nome Servizio --}}
    <div class="mb-3">
      <label
        for="name"
        class="form-label"
      >
        Nome Servizio
        <span class="text-danger">*</span>
      </label>
      <input
        type="text"
        class="form-control @error('name') is-invalid @enderror"
        id="name"
        name="name"
        value="{{ old('name', $service->name) }}"
        required
      />
      @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Slug (generato automaticamente ma modificabile) --}}
    <div class="mb-3">
      <label
        for="slug"
        class="form-label"
      >
        Slug
        <span class="text-danger">*</span>
      </label>
      <div class="input-group">
        <input
          type="text"
          class="form-control @error('slug') is-invalid @enderror"
          id="slug"
          name="slug"
          value="{{ old('slug', $service->slug) }}"
          required
        />
        <button
          class="btn btn-outline-secondary"
          type="button"
          id="editSlugBtn"
          title="Modifica manualmente lo slug"
        >
          <span class="material-symbols-outlined fs-6">edit</span>
        </button>
      </div>
      <div class="form-text small text-muted">
        URL amichevole per il servizio. Generato automaticamente dal nome.
      </div>
      @error('slug')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>

    {{-- Descrizione --}}
    <div class="mb-3">
      <label
        for="description"
        class="form-label"
      >
        Descrizione
        <span class="text-danger">*</span>
      </label>
      <textarea
        class="form-control @error('description') is-invalid @enderror"
        id="description"
        name="description"
        rows="5"
        required
      >
{{ old('description', $service->description) }}</textarea
      >
      @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Pubblico di Riferimento --}}
    <div class="mb-3">
      <label
        for="target_audience"
        class="form-label"
      >
        Pubblico di Riferimento
      </label>
      <textarea
        class="form-control @error('target_audience') is-invalid @enderror"
        id="target_audience"
        name="target_audience"
        rows="3"
      >
{{ old('target_audience', $service->target_audience) }}</textarea
      >
      @error('target_audience')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Obiettivi --}}
    <div class="mb-3">
      <label
        for="objectives"
        class="form-label"
      >
        Obiettivi
      </label>
      <textarea
        class="form-control @error('objectives') is-invalid @enderror"
        id="objectives"
        name="objectives"
        rows="4"
      >
{{ old('objectives', $service->objectives) }}</textarea
      >
      @error('objectives')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Modalità --}}
    <div class="mb-3">
      <label
        for="modalities"
        class="form-label"
      >
        Modalità di Erogazione
      </label>
      <textarea
        class="form-control @error('modalities') is-invalid @enderror"
        id="modalities"
        name="modalities"
        rows="3"
      >
{{ old('modalities', $service->modalities) }}</textarea
      >
      @error('modalities')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>

  {{-- Colonna Laterale (Impostazioni) --}}
  <div class="col-md-4">
    <div class="card bg-light">
      <div class="card-body">
        <h5 class="card-title mb-3">Impostazioni</h5>

        {{-- Categoria Target --}}
        <div class="mb-3">
          <label
            for="target_category_id"
            class="form-label"
          >
            Categoria Target
            <span class="text-danger">*</span>
          </label>
          <select
            class="form-select @error('target_category_id') is-invalid @enderror"
            id="target_category_id"
            name="target_category_id"
            required
          >
            <option
              value=""
              disabled
              {{ old('target_category_id', $service->target_category_id) ? '' : 'selected' }}
            >
              Seleziona una categoria...
            </option>
            @foreach ($targetCategories as $category)
              <option
                value="{{ $category->id }}"
                {{ old('target_category_id', $service->target_category_id) == $category->id ? 'selected' : '' }}
              >
                {{ $category->name }}
              </option>
            @endforeach
          </select>
          @error('target_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Stato Attivo/Non Attivo --}}
        <div class="form-check form-switch">
          <input
            class="form-check-input"
            type="checkbox"
            role="switch"
            id="is_active"
            name="is_active"
            value="1"
            {{ old('is_active', $service->is_active) ? 'checked' : '' }}
          />
          <label
            class="form-check-label"
            for="is_active"
          >
            Servizio Attivo
          </label>
        </div>
        <div class="form-text small mt-1">
          Se disattivato, il servizio non sarà visibile nella pagina pubblica.
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Pulsanti di Azione --}}
<div class="mt-4">
  <button
    type="submit"
    class="btn btn-primary me-2"
  >
    Salva Servizio
  </button>
  <a
    href="{{ route('admin.services.index') }}"
    class="btn btn-outline-secondary"
  >
    Annulla
  </a>
</div>
