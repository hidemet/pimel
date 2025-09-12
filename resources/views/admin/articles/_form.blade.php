
@csrf

{{-- Titolo --}}
<div class="mb-3">
  <label
    for="title"
    class="form-label"
  >
    Titolo
    <span class="text-danger">*</span>
  </label>
  <input
    type="text"
    class="form-control @error('title') is-invalid @enderror"
    id="title"
    name="title"
    value="{{ old('title', $article->title ?? '') }}"
    required
  />
  @error('title')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

{{-- Slug --}}
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
      value="{{ old('slug', $article->slug ?? '') }}"
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
  @error('slug')
    <div class="invalid-feedback d-block">{{ $message }}</div>
  @enderror

  <div class="form-text small text-muted">
    URL amichevole per l'articolo.
  </div>
</div>



{{-- Description  --}}
<div class="mb-3">
  <label
    for="description"
    class="form-label"
  >
    Riepilogo Breve (Description)
  </label>
  <textarea
    class="form-control @error('description') is-invalid @enderror"
    id="description"
    name="description"
    rows="3"
  >
{{ old('description', $article->description ?? '') }}</textarea
  >
  <div class="form-text text-muted">
      Breve riassunto dell'articolo (max 1000 caratteri). Se vuoto, verrà
      generato automaticamente.
    </div>
    @error('description')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  {{-- Corpo dell'Articolo --}}
  <div class="mb-3">
    <label
      for="body"
      class="form-label"
    >
      Corpo dell'Articolo
      <span class="text-danger">*</span>
    </label>
    <textarea
      class="form-control @error('body') is-invalid @enderror"
      id="body"
      name="body"
      rows="15"
      required
    >
{{ old('body', $article->body ?? '') }}</textarea
    >
    <div class="form-text text-muted">
      Contenuto completo dell'articolo. Supporta HTML di base.
    </div>
    @error('body')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  {{-- Immagine di Copertina --}}
  <div class="mb-3">
    <label
      for="image_path"
      class="form-label"
    >
      Immagine di Copertina
    </label>
    <input
      type="file"
      class="form-control @error('image_path') is-invalid @enderror"
      id="image_path"
      name="image_path"
      accept="image/*"
    />
    <div class="form-text text-muted">
      Carica un'immagine di copertina per l'articolo (formati supportati: JPG,
      PNG, GIF).
    </div>
    @error('image_path')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    {{-- Anteprima immagine attuale --}}
    @if (isset($article) && $article->image_path)
      <div class="mt-2">
        <p class="fw-semibold mb-1 text-dark">Immagine attuale:</p>
        <img
          src="{{ asset('storage/' . $article->image_path) }}"
          alt="Immagine attuale"
          class="img-thumbnail"
          style="max-width: 200px; max-height: 150px"
        />
        <div class="form-check mt-2">
          <input
            class="form-check-input"
            type="checkbox"
            id="remove_image"
            name="remove_image"
            value="1"
          />
          <label
            class="form-check-label text-dark"
            for="remove_image"
          >
            Rimuovi immagine attuale
          </label>
        </div>
      </div>
    @endif
  </div>

  {{-- Rubrica --}}
  <div class="mb-3">
    <label
      for="rubric_id"
      class="form-label"
    >
      Rubrica
      <span class="text-danger">*</span>
    </label>
    <select
      class="form-select @error('rubric_id') is-invalid @enderror"
      id="rubric_id"
      name="rubric_id"
      required
    >
      <option
        value=""
        disabled
        {{ old('rubric_id', $article->rubric_id ?? '') ? '' : 'selected' }}
      >
        Seleziona una rubrica...
      </option>
      @foreach ($rubrics as $rubric)
        <option
          value="{{ $rubric->id }}"
          {{ old('rubric_id', $article->rubric_id ?? '') == $rubric->id ? 'selected' : '' }}
        >
          {{ $rubric->name }}
        </option>
      @endforeach
    </select>
    @error('rubric_id')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  {{-- Autore --}}
  <div class="mb-3">
    <label
      for="author_name"
      class="form-label"
    >
      Autore
    </label>
    <input
      type="text"
      class="form-control"
      id="author_name"
      name="author_name"
      value="{{ Auth::user()->name }}"
      readonly
    />
    <div class="form-text text-muted">
      L'articolo sarà automaticamente assegnato all'utente attualmente connesso.
    </div>
  </div>

  {{-- Data di Pubblicazione --}}
  <div class="mb-3">
    <label
      for="published_at"
      class="form-label"
    >
      Data di Pubblicazione
    </label>
    <input
      type="datetime-local"
      class="form-control @error('published_at') is-invalid @enderror"
      id="published_at"
      name="published_at"
      value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}"
      required
    />
    <div class="form-text text-muted">
        Imposta una data e un'ora per la pubblicazione.
    </div>
    @error('published_at')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  {{-- Pulsanti di Azione --}}
  <div class="mt-4 border-top pt-3">
    <button
      type="submit"
      class="btn btn-primary me-2"
    >
      Salva Articolo
    </button>
    <a
      href="{{ route('admin.articles.index') }}"
      class="btn btn-outline-secondary"
    >
      Annulla
    </a>
  </div>
</div>
