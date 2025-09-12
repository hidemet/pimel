@props([
  'article',
])

<div class="list-group-item py-4 px-3 bg-transparent">
  <article>
    <div class="row g-3 align-items-center">
      <div class="col flex-shrink-1">
        <header class="mb-2">
          {{-- RUBRICA --}}
          <div class="mb-2">
            <a
              href="{{ route('blog.index', ['rubrica' => $article->rubric->slug]) }}"
              class="text-decoration-none text-dark me-2 small fw-medium"
            >
              {{ $article->rubric->name }}
            </a>
          </div>
          {{-- TITOLO --}}
          <h3 class="h5 fw-bold mb-2 lh-tight">
            <a
              href="{{ route('blog.show', $article->slug) }}"
              class="text-decoration-none text-dark"
            >
              {{ $article->title }}
            </a>
          </h3>
        </header>

        <p class="text-muted small mb-0 lh-sm d-none d-sm-block">
          {{ Str::limit(strip_tags($article->description ?? ''), 150) }}
        </p>
      </div>

      {{-- COLONNA IMMAGINE --}}
      <div class="col-4 col-md-3 flex-shrink-0">
        <a
          href="{{ route('blog.show', $article->slug) }}"
          class="d-block text-decoration-none article-image-link"
        >
          <div class="ratio ratio-1x1 overflow-hidden rounded">
            @php
              // Fornisce una immagine di placeholder se non è presente
              $imageUrl = $article->image_url ?? asset('assets/img/placeholder_articolo.png');
            @endphp

            <img
              src="{{ $imageUrl }}"
              alt="Immagine copertina articolo: {{ $article->title }}"
              loading="lazy"
              class="object-fit-cover w-100 h-100"
            />
          </div>
        </a>
      </div>
    </div>

    {{-- RIGA SEPARATA PER I METADATI (FOOTER) --}}
    <footer
      class="article-meta d-flex align-items-center small text-muted w-100 mt-2 pt-2"
    >
      <div class="flex-grow-1">
        <span title="{{ $article->published_at->format('d F Y H:i') }}">
          {{ $article->published_at->format('d M Y') }}
        </span>
        <span class="mx-1">•</span>
        <span>{{ $article->reading_time }} min lettura</span>
      </div>

      <div class="d-flex align-items-center flex-shrink-0 ms-auto">
        {{-- Codice per UTENTE AUTENTICATO --}}
        @auth
          <button
            type="button"
            class="btn btn-sm btn-link p-0 me-3 like-button text-decoration-none {{ $article->has_liked ?? false ? 'text-primary' : 'text-muted' }}"
            data-article-id="{{ $article->id }}"
          >
            <i
              class="bi {{ $article->has_liked ?? false ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }} me-1"
            ></i>
            <span class="like-count">{{ $article->likes_count ?? 0 }}</span>
          </button>
        @else
          <button
            type="button"
            class="btn btn-sm btn-link p-0 me-3 text-muted text-decoration-none"
            data-bs-toggle="modal"
            data-bs-target="#guestActionModal"
          >
            <i class="bi bi-hand-thumbs-up me-1"></i>
            <span class="like-count">{{ $article->likes_count ?? 0 }}</span>
          </button>
        @endauth

        <a
          href="{{ route('blog.show', $article->slug) }}#comments"
          class="btn btn-sm btn-link text-muted text-decoration-none p-0"
        >
          <i class="bi bi-chat me-1"></i>
          <span class="comment-count">
            {{ $article->comments_count ?? 0 }}
          </span>
        </a>
      </div>
    </footer>
  </article>
</div>
