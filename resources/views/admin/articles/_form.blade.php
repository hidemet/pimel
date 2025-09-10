{{-- resources/views/admin/articles/_form.blade.php --}}
{{--
  Questo partial contiene la logica del form per la creazione e modifica di un articolo.
  Richiede le seguenti variabili:
  - $article: istanza di App\Models\Article (vuota per create, popolata per edit)
  - $rubrics: Collection di tutte le rubriche disponibili
  - $authors: Collection di tutti gli utenti che possono essere autori (es. admin)
--}}
@csrf

{{-- Sezione: Dettagli Articolo (Titolo, Description, Corpo, Immagine) --}}
<div class="card shadow-sm mb-4">
  <div class="card-header bg-light">
    <h5 class="mb-0">Dettagli Articolo</h5>
  </div>
  <div class="card-body">
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

    {{-- Description (Riepilogo Breve) --}}
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
      <div class="form-text">
        Breve riassunto dell'articolo (max 1000 caratteri). Se vuoto, verrà
        generato automaticamente.
      </div>
      @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Corpo dell'Articolo (Textarea semplice per ora) --}}
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
      <div class="form-text">
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
      <div class="form-text">
        Carica un'immagine per la copertina dell'articolo (max 4MB, formati:
        jpeg, png, jpg, gif, svg, webp).
      </div>
      @error('image_path')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror

      {{-- Anteprima Immagine Esistente / Nuova --}}
      @php
        // Recupera l'URL dell'immagine attuale per l'anteprima iniziale, se presente.
        // Usiamo $article->image_url che è un accessor nel modello Article.
        $currentImageUrl = $article->image_path ? $article->image_url : null;
      @endphp

      <div
        class="mt-3 current-image-preview"
        style="{{ $currentImageUrl ? 'display: block;' : 'display: none;' }}"
      >
        <p class="mb-2 fw-semibold">
          @if ($currentImageUrl)
            Immagine attuale:
          @else
              Anteprima nuova immagine:
          @endif
        </p>
        <img
          src="{{ $currentImageUrl ?? '' }}"
          alt="Anteprima immagine"
          class="img-thumbnail mb-2"
          style="max-width: 200px"
        />
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            id="remove_image"
            name="remove_image"
            value="1"
            {{ old('remove_image') ? 'checked' : '' }}
          />
          <label
            class="form-check-label"
            for="remove_image"
          >
            Rimuovi immagine attuale
          </label>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Sezione: Impostazioni Articolo (Autore, Rubriche, Tempo di Lettura, Stato, Data/Ora Pubblicazione) --}}
<div class="card shadow-sm mb-4">
  <div class="card-header bg-light">
    <h5 class="mb-0">Impostazioni Articolo</h5>
  </div>
  <div class="card-body">
    {{-- NUOVO BLOCCO RUBRICHE (da inserire al suo posto) --}}
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
          {{ old('rubric_id', $article->rubric_id) ? '' : 'selected' }}
        >
          Seleziona una rubrica...
        </option>
        @foreach ($rubrics as $rubric)
          <option
            value="{{ $rubric->id }}"
            {{ old('rubric_id', $article->rubric_id) == $rubric->id ? 'selected' : '' }}
          >
            {{ $rubric->name }}
          </option>
        @endforeach
      </select>
      @error('rubric_id')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
    {{-- Tempo di Lettura --}}
    <div class="mb-3">
      <label
        for="reading_time"
        class="form-label"
      >
        Tempo di Lettura (minuti)
      </label>
      <input
        type="number"
        class="form-control @error('reading_time') is-invalid @enderror"
        id="reading_time"
        name="reading_time"
        value="{{ old('reading_time', $article->reading_time ?? '') }}"
        min="0"
      />
      <div class="form-text">
        Tempo approssimativo per la lettura (es. 5 per 5 minuti).
      </div>
      @error('reading_time')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Stato e Data/Ora di Pubblicazione --}}
    <div class="mb-3">
      <label
        for="status"
        class="form-label"
      >
        Stato
        <span class="text-danger">*</span>
      </label>
      <select
        class="form-select @error('status') is-invalid @enderror"
        id="status"
        name="status"
        required
      >
        <option
          value="draft"
          {{ old('status', $article->status) == 'draft' ? 'selected' : '' }}
        >
          Bozza
        </option>
        <option
          value="published"
          {{ old('status', $article->status) == 'published' ? 'selected' : '' }}
        >
          Pubblicato
        </option>
        <option
          value="scheduled"
          {{ old('status', $article->status) == 'scheduled' ? 'selected' : '' }}
        >
          Pianificato
        </option>
        <option
          value="archived"
          {{ old('status', $article->status) == 'archived' ? 'selected' : '' }}
        >
          Archiviato
        </option>
      </select>
      @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Campi Data e Ora per stato "Pianificato" (condizionali) --}}
    @php
      $isScheduled = old('status', $article->status) == 'scheduled';
      $publishedDate = $article->published_at ? $article->published_at->format('Y-m-d') : '';
      $publishedTime = $article->published_at ? $article->published_at->format('H:i') : '';
    @endphp

    <div
      id="scheduled_fields"
      class="mb-3"
      style="display: {{ $isScheduled ? 'block' : 'none' }}"
    >
      <p class="form-label fw-semibold">Data e Ora di Pubblicazione</p>
      <div class="row">
        <div class="col">
          <label
            for="published_at_date"
            class="visually-hidden"
          >
            Data
          </label>
          <input
            type="date"
            class="form-control @error('published_at_date') is-invalid @enderror"
            id="published_at_date"
            name="published_at_date"
            value="{{ old('published_at_date', $publishedDate) }}"
            {{ $isScheduled ? 'required' : '' }}
          />
          @error('published_at_date')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col">
          <label
            for="published_at_time"
            class="visually-hidden"
          >
            Ora
          </label>
          <input
            type="time"
            class="form-control @error('published_at_time') is-invalid @enderror"
            id="published_at_time"
            name="published_at_time"
            value="{{ old('published_at_time', $publishedTime) }}"
            {{ $isScheduled ? 'required' : '' }}
          />
          @error('published_at_time')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="form-text">
        Specificare data e ora future per la pubblicazione automatica.
      </div>
    </div>
  </div>
</div>

<div class="mt-4 text-end">
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

@push('scripts')
  @once
    {{-- Assicura che lo script sia incluso una sola volta --}}
    <script>
      // Utilizziamo la sintassi standard di jQuery per eseguire il codice
      // solo dopo che il DOM è stato completamente caricato.
      $(function () {
        // --- Logica per mostrare/nascondere i campi di schedulazione ---
        const $statusSelect = $('#status');
        const $scheduledFields = $('#scheduled_fields');
        const $publishedAtDateInput = $('#published_at_date');
        const $publishedAtTimeInput = $('#published_at_time');

        function toggleScheduledFields() {
          if ($statusSelect.val() === 'scheduled') {
            $scheduledFields.show();
            $publishedAtDateInput.prop('required', true);
            $publishedAtTimeInput.prop('required', true);
          } else {
            $scheduledFields.hide();
            $publishedAtDateInput.prop('required', false);
            $publishedAtTimeInput.prop('required', false);
          }
        }

        if ($statusSelect.length) {
          $statusSelect.on('change', toggleScheduledFields);
          // Imposta lo stato iniziale al caricamento della pagina
          toggleScheduledFields();
        }

        // --- Logica per Anteprima Immagine e Checkbox Rimuovi ---
        const $imageInput = $('#image_path');
        const $removeImageCheckbox = $('#remove_image');
        const $currentImagePreviewContainer = $('.current-image-preview');
        const currentArticleHasImage = !!'{{ $article->image_path }}';
        const currentArticleImageUrl = '{{ $article->image_url ?? '' }}';

        // Funzione per aggiornare l'anteprima dell'immagine
        function updateImagePreview(file) {
          const $imgElement =
            $currentImagePreviewContainer.find('.img-thumbnail');
          const $labelElement =
            $currentImagePreviewContainer.find('p.fw-semibold');

          if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
              $imgElement.attr('src', e.target.result);
              $labelElement.text('Anteprima nuova immagine:');
              $currentImagePreviewContainer.show();
              $imgElement.css('opacity', '1');
              $removeImageCheckbox.prop('checked', false); // Deseleziona "Rimuovi"
            };
            reader.readAsDataURL(file);
          } else {
            // Se non c'è file selezionato (es. l'utente ha cancellato la selezione)
            if (currentArticleHasImage) {
              $imgElement.attr('src', currentArticleImageUrl);
              $labelElement.text('Immagine attuale:');
              $currentImagePreviewContainer.show();
              // Mostra l'immagine non opaca solo se "rimuovi" non è spuntato
              if (!$removeImageCheckbox.is(':checked')) {
                $imgElement.css('opacity', '1');
              }
            } else {
              $currentImagePreviewContainer.hide();
              $imgElement.attr('src', '');
            }
          }
        }

        // Funzione per gestire il cambio dello stato del checkbox "Rimuovi immagine"
        function handleRemoveImageCheckbox() {
          const $imgElement =
            $currentImagePreviewContainer.find('.img-thumbnail');

          if ($removeImageCheckbox.is(':checked')) {
            // Svuota il campo file per evitare l'upload e aggiorna l'anteprima
            $imageInput.val('');
            $imgElement.css('opacity', '0.5');
          } else {
            $imgElement.css('opacity', '1');
            // Se l'utente deseleziona "Rimuovi" e non c'è un nuovo file,
            // ripristina la visualizzazione originale.
            if (currentArticleHasImage && !$imageInput.val()) {
              $imgElement.attr('src', currentArticleImageUrl);
              $currentImagePreviewContainer
                .find('p.fw-semibold')
                .text('Immagine attuale:');
            }
          }
        }

        // Aggiungi event listener per l'input file
        if ($imageInput.length) {
          $imageInput.on('change', function () {
            updateImagePreview(this.files[0]);
          });
        }

        // Aggiungi event listener per il checkbox di rimozione
        if ($removeImageCheckbox.length) {
          $removeImageCheckbox.on('change', handleRemoveImageCheckbox);
        }

        // Al caricamento della pagina, se l'articolo ha già un'immagine,
        // assicurati che la preview sia visibile e l'opacità corretta.
        if (currentArticleHasImage && $currentImagePreviewContainer.length) {
          if ($removeImageCheckbox.is(':checked')) {
            $currentImagePreviewContainer
              .find('.img-thumbnail')
              .css('opacity', '0.5');
          }
          $currentImagePreviewContainer.show();
        }
      });
    </script>
  @endonce
@endpush
