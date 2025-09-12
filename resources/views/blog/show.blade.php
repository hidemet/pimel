<x-app-layout>
  @section('title', $article->title . ' - Blog PIMEL')
  @section('description', $article->description ? Str::limit(strip_tags($article->description), 160) : Str::limit(strip_tags($article->body), 160))

  <div class="container-fluid px-0">
    <div class="container py-4">
      {{-- Gestione Toast di Sessione (es. per commenti) --}}
      @if (session('toast'))
        {{-- Questo script viene eseguito solo se c'è un toast in sessione --}}
        @push('page-scripts')
          <script>
            document.addEventListener('DOMContentLoaded', function () {
              const toastData = @json(session('toast'));
              if (toastData) {
                window.showToast(
                  toastData.message,
                  toastData.type,
                  toastData.title
                );
              }
            });
          </script>
        @endpush
      @endif

      <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

          {{-- Header Articolo --}}
          <header class="mb-5 text-center">
            @if ($article->rubric)
              <div class="mb-3">
                <a
                  href="{{ route('blog.index', ['rubrica' => $article->rubric->slug]) }}"
                  class="btn btn-outline-dark btn-sm rounded-pill px-3 py-1"
                >
                  {{ $article->rubric->name }}
                </a>
              </div>
            @endif

            <h1 class="display-5 fw-semibold lh-1 mb-4">
              {{ $article->title }}
            </h1>

            <div class="article-metadata-block mx-auto pt-3">
              <div
                class="d-flex flex-column align-items-center text-center mb-3"
              >
                <div class="mb-2">
                  <img
                    src="{{ asset('assets/img/foto-profilo.png') }}"
                    alt="Foto profilo di {{ $article->author->name }}"
                    class="img-fluid rounded-circle shadow-sm"
                    style="width: 80px; height: 80px; object-fit: cover"
                  />
                </div>
                <div>
                  <span class="small text-muted">di</span>
                  <span class="fw-medium">{{ $article->author->name }}</span>
                </div>
              </div>

              <div
                class="d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center small text-muted pt-3"
              >
                <div class="text-center text-sm-start mb-2 mb-sm-0">
                  @if ($article->published_at)
                    <span
                      title="{{ $article->published_at->translatedFormat('d F Y H:i') }}"
                    >
                      {{ $article->published_at->translatedFormat('d M Y') }}
                    </span>
                  @endif

                  @if ($article->published_at && $article->reading_time)
                    <span class="mx-1 d-none d-sm-inline">•</span>
                  @endif

                  @if ($article->reading_time)
                    <span class="d-block d-sm-inline mt-1 mt-sm-0">
                      {{ $article->reading_time }} min lettura
                    </span>
                  @endif
                </div>

                <div class="d-flex align-items-center gap-3">
                  @php
                    $hasLiked = $article->has_liked ?? false;
                  @endphp

                  @auth
                    <button
                      type="button"
                      class="btn btn-sm btn-link p-0 text-decoration-none like-button {{ $hasLiked ? 'text-primary' : 'text-muted' }}"
                      data-article-id="{{ $article->id }}"
                      title="Mi Piace"
                    >
                      <i
                        class="bi {{ $hasLiked ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }} me-1"
                      ></i>
                      <span class="like-count fw-semibold">
                        {{ $article->likes_count ?? 0 }}
                      </span>
                    </button>
                  @else
                    <button
                      type="button"
                      class="btn btn-sm btn-link p-0 text-decoration-none text-muted"
                      data-bs-toggle="modal"
                      data-bs-target="#guestActionModal"
                      title="Accedi per mettere Mi Piace"
                    >
                      <i class="bi bi-hand-thumbs-up me-1"></i>
                      <span class="like-count fw-semibold">
                        {{ $article->likes_count ?? 0 }}
                      </span>
                    </button>
                  @endauth

                  <a
                    href="#comments"
                    class="d-flex align-items-center text-decoration-none text-muted"
                    title="{{ $article->comments->where('is_approved', true)->count() }} Commenti"
                  >
                    <i class="bi bi-chat-left-text me-1"></i>
                    <span class="fw-semibold">
                      {{ $article->comments->where('is_approved', true)->count() }}
                    </span>
                  </a>
                </div>
              </div>
            </div>
          </header>

          {{-- Immagine  --}}
          @if ($article->image_path)
            <figure class="figure mb-5 w-100">
              <img
                src="{{ $article->image_url }}"
                class="figure-img img-fluid rounded shadow-lg"
                alt="{{ $article->title }}"
                loading="lazy"
              />
            </figure>
          @endif

          <article
            class="article-content fs-5 mb-5"
            id="article-content"
          >
            {!! $article->body !!}
          </article>

          {{-- Sezione Autore e Commenti --}}
          @include('blog.partials._author-box', ['author' => $article->author])
          @include(
            'blog.partials._comments-section',
            [
              'article' => $article,
              'comments' => $comments ?? collect(),
              'pendingComments' => $pendingComments ?? collect(),
              'totalVisibleComments' =>
                $totalVisibleComments ?? ($article->approved_comments_count ?? 0),
            ]
          )
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
