 @push('page-scripts')
    @vite('resources/js/pages/admin-articles-index.js')
    @endpush

<x-app-layout>
  @section('title', 'Gestione Articoli')

  <x-slot name="pageHeader">
    <div class="container">
      <div class="row">
        <div class="col">
          <x-layout.page-header title="Gestione Articoli">
            <a
              href="{{ route('admin.articles.create') }}"
              class="btn btn-primary"
            >
              <span class="material-symbols-outlined fs-6 me-1 align-middle">
                add_circle
              </span>
              Nuovo Articolo
            </a>
          </x-layout.page-header>
        </div>
      </div>
    </div>
  </x-slot>

  <div class="container py-4">
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

    <div class="card shadow-sm rounded-3">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover rounded-3 mb-0 overflow-hidden">
            <thead class="table-dark">
              <tr>
                <th scope="col">Immagine</th>
                <th scope="col">Titolo</th>
                <th scope="col">Rubrica</th>
                <th scope="col">Stato</th>
                <th
                  scope="col"
                  class="text-center"
                >
                  Statistiche
                </th>
                <th
                  scope="col"
                  class="text-end"
                >
                  Azioni
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse ($articles as $article)
                <tr id="article-row-{{ $article->id }}">
                  <td>
                    <a href="{{ route('admin.articles.edit', $article) }}">
                      <img
                        src="{{ $article->image_url ?? asset('assets/img/placeholder_articolo.png') }}"
                        alt="Copertina: {{ Str::limit($article->title, 30) }}"
                        class="img-thumbnail object-fit-cover w-100 h-100 rounded"
                      />
                    </a>
                  </td>
                  <td class="align-middle">
                    <a
                      href="{{ route('admin.articles.edit', $article) }}"
                      class="text-dark fw-semibold text-decoration-none"
                    >
                      {{ $article->title }}
                    </a>
                    <div class="small text-muted">
                      di {{ $article->author->name ?? 'N/A' }}
                    </div>
                  </td>
                  <td class="align-middle">
                    @if ($article->rubric)
                      <span class="badge bg-light text-dark border">
                        {{ $article->rubric->name }}
                      </span>
                    @else
                      <span class="text-muted small">Nessuna</span>
                    @endif
                  </td>
                  <td class="align-middle">
                    @if ($article->published_at && $article->published_at <= now())
                      <div class="small text-muted">
                        {{ $article->published_at->isoFormat('D MMM YYYY') }}
                      </div>
                    @elseif ($article->published_at && $article->published_at > now())
                      <span
                        class="badge bg-warning-subtle text-warning-emphasis"
                      >
                        Programmato
                      </span>
                      <div class="small text-muted">
                        {{ $article->published_at->isoFormat('D MMM YYYY') }}
                      </div>
                    @else
                      <span
                        class="badge bg-secondary-subtle text-secondary-emphasis"
                      >
                        Bozza
                      </span>
                    @endif
                  </td>
                  <td class="align-middle text-center small text-muted">
                    <div title="Mi Piace">
                      <span
                        class="material-symbols-outlined fs-6 me-1 align-middle"
                      >
                        thumb_up
                      </span>
                      {{ $article->likes_count ?? 0 }}
                    </div>
                    <div title="Commenti">
                      <span
                        class="material-symbols-outlined fs-6 me-1 align-middle"
                      >
                        chat_bubble
                      </span>
                      {{ $article->comments_count ?? 0 }}
                    </div>
                  </td>
                  <td class="align-middle text-end">
                    <div class="btn-group">
                      <a
                        href="{{ route('blog.show', $article->slug) }}"
                        target="_blank"
                        class="btn btn-sm btn-outline-secondary"
                        title="Visualizza"
                      >
                        <span
                          class="material-symbols-outlined fs-6 align-middle"
                        >
                          visibility
                        </span>
                      </a>
                      <a
                        href="{{ route('admin.articles.edit', $article) }}"
                        class="btn btn-sm btn-outline-primary"
                        title="Modifica"
                      >
                        <span
                          class="material-symbols-outlined fs-6 align-middle"
                        >
                          edit
                        </span>
                      </a>
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-danger delete-article-btn"
                        data-article-id="{{ $article->id }}"
                        data-article-title="{{ addslashes(Str::limit($article->title, 50)) }}"
                        data-delete-url="{{ route('admin.articles.destroy', $article) }}"
                        title="Elimina"
                      >
                        <span
                          class="material-symbols-outlined fs-6 align-middle"
                        >
                          delete
                        </span>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td
                    colspan="6"
                    class="text-center py-5"
                  >
                    <h4 class="text-muted">Nessun articolo trovato.</h4>
                    <p class="text-muted">Inizia creando un nuovo articolo.</p>
                    <a
                      href="{{ route('admin.articles.create') }}"
                      class="btn btn-primary mt-2"
                    >
                      <span
                        class="material-symbols-outlined fs-6 me-1 align-middle"
                      >
                        add_circle
                      </span>
                      Crea il primo Articolo
                    </a>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if ($articles->hasPages())
        <div class="card-footer">
          {{ $articles->withQueryString()->links() }}
        </div>
      @endif
    </div>
  </div>
  {{--
    @push('page-scripts')
    @vite('resources/js/pages/admin-articles-index.js')
    @endpush
  --}}
</x-app-layout>
