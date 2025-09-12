@csrf

{{-- Nome Rubrica --}}
<div class="mb-3">
  <label
    for="name"
    class="form-label"
  >
    Nome Rubrica
    <span class="text-danger">*</span>
  </label>
  <input
    type="text"
    class="form-control @error('name') is-invalid @enderror"
    id="name"
    name="name"
    value="{{ old('name', $rubric->name ?? '') }}"
    required
  />
  @error('name')
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
      value="{{ old('slug', $rubric->slug ?? '') }}"
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
    URL amichevole per la rubrica. Generato automaticamente dal nome.
  </div>
</div>

{{-- Pulsanti di Azione --}}
<div class="mt-4 border-top pt-3">
  <button
    type="submit"
    class="btn btn-primary me-2"
  >
    Salva Rubrica
  </button>
  <a
    href="{{ route('admin.rubrics.index') }}"
    class="btn btn-outline-secondary"
  >
    Annulla
  </a>
</div>
