{{-- resources/views/admin/articles/_form.blade.php --}}
@csrf
<div class="row">
    <div class="col-md-8">
        {{-- Titolo --}}
        <div class="mb-3">
            <label for="title" class="form-label">Titolo <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                value="{{ old('title', $article->title ?? '') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Body --}}
        <div class="mb-3">
            <label for="body" class="form-label">Contenuto <span class="text-danger">*</span></label>
            <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="15"
                required>{{ old('body', $article->body ?? '') }}</textarea>
            @error('body')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            {{-- TODO: Integrare un editor WYSIWYG qui --}}
        </div>

        {{-- Excerpt --}}
        <div class="mb-3">
            <label for="excerpt" class="form-label">Estratto (Opzionale)</label>
            <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
            @error('excerpt')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        {{-- Stato --}}
        <div class="mb-3">
            <label for="status" class="form-label">Stato <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                @foreach ($articleStatuses as $key => $value)
                    <option value="{{ $key }}"
                        {{ old('status', $article->status ?? 'draft') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Data Pubblicazione --}}
        <div class="mb-3">
            <label for="published_at_date" class="form-label">Data Pubblicazione (Opzionale)</label>
            <div class="input-group">
                <input type="date" class="form-control @error('published_at_date') is-invalid @enderror"
                    id="published_at_date" name="published_at_date"
                    value="{{ old('published_at_date', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d') : '') }}">
                <input type="time" class="form-control @error('published_at_time') is-invalid @enderror"
                    id="published_at_time" name="published_at_time"
                    value="{{ old('published_at_time', isset($article) && $article->published_at ? $article->published_at->format('H:i') : '') }}">
            </div>
            @error('published_at_date')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            @error('published_at_time')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        {{-- Rubriche --}}
        <div class="mb-3">
            <label class="form-label">Rubriche (Opzionale)</label>
            <div class="overflow-auto border rounded p-2" style="max-height: 150px;">
                @foreach ($rubrics as $rubric)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="rubric_ids[]" value="{{ $rubric->id }}"
                            id="rubric_{{ $rubric->id }}"
                            @if (is_array(old('rubric_ids')) && in_array($rubric->id, old('rubric_ids'))) checked
                               @elseif(isset($article) && $article->rubrics->contains($rubric->id) && !is_array(old('rubric_ids')))
                                   checked @endif>
                        <label class="form-check-label" for="rubric_{{ $rubric->id }}">
                            {{ $rubric->name }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('rubric_ids')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
            @error('rubric_ids.*')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Immagine di Copertina --}}
        <div class="mb-3">
            <label for="image_path" class="form-label">Immagine di Copertina (Opzionale)</label>
            <input type="file" class="form-control @error('image_path') is-invalid @enderror" id="image_path"
                name="image_path">
            @error('image_path')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @if (isset($article) && $article->image_path)
                <div class="mt-2">
                    <img src="{{ $article->image_url }}" alt="Anteprima"
                        style="max-height: 100px; border-radius: 0.25rem;">
                    <p class="small text-muted mt-1">Immagine attuale. Scegli un nuovo file per sostituirla.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<hr>
<h6 class="mb-3">SEO e Metadati (Opzionali)</h6>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="meta_description" class="form-label">Meta Description</label>
        <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
            name="meta_description" rows="2">{{ old('meta_description', $article->meta_description ?? '') }}</textarea>
        @error('meta_description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="meta_keywords" class="form-label">Meta Keywords (separate da virgola)</label>
        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords"
            name="meta_keywords" value="{{ old('meta_keywords', $article->meta_keywords ?? '') }}">
        @error('meta_keywords')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="reading_time" class="form-label">Tempo di lettura (minuti)</label>
        <input type="number" class="form-control @error('reading_time') is-invalid @enderror" id="reading_time"
            name="reading_time" value="{{ old('reading_time', $article->reading_time ?? '') }}" min="0">
        @error('reading_time')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-success">
        <span class="material-symbols-outlined fs-6 align-middle me-1">save</span>
        Salva Articolo
    </button>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary ms-2">Annulla</a>
</div>
