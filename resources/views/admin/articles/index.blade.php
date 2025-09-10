@push('page-scripts')
  @vite('resources/js/pages/admin-articles-index.js')
@endpush

<x-app-layout>
  @section('title', 'Gestione Articoli - Admin PIMEL')

  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <x-slot name="pageHeader">
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
  </x-slot>

  <div class="container py-4 admin-articles-page">
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

    {{-- Riga 1: Segmented Buttons per Stato --}}
    <div class="nav nav-pills-segmented mb-3">
      @foreach ($articleDisplayStatuses as $statusKey => $statusData)
        <a
          href="{{ route('admin.articles.index', array_merge(request()->except(['status', 'page']), ['status' => $statusKey])) }}"
          class="nav-link {{ $currentStatusFilter == $statusKey ? 'active' : '' }} d-flex align-items-center gap-1 flex-wrap justify-content-center px-2 py-1"
        >
          <span class="material-symbols-outlined fs-6">
            {{ $statusData['icon'] }}
          </span>
          <span class="small">{{ $statusData['text'] }}</span>
          <span
            class="badge rounded-pill {{ $currentStatusFilter == $statusKey ? 'bg-white text-primary' : 'bg-secondary bg-opacity-25' }} ms-1"
          >
            {{ $statusData['count'] }}
          </span>
        </a>
      @endforeach
    </div>

    {{-- Riga 2: Form per Ricerca, Filtri e Ordinamento --}}
    <div class="mb-4">
      <form
        id="mainFilterSortForm"
        action="{{ route('admin.articles.index') }}"
        method="GET"
        class="row g-2 align-items-center"
      >
        {{-- Input nascosto per mantenere lo stato --}}
        <input
          type="hidden"
          name="status"
          value="{{ $currentStatusFilter }}"
        />

        {{-- Ricerca testuale --}}
        <div class="col">
          <label
            for="search_term_main"
            class="visually-hidden"
          >
            Cerca Articoli
          </label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input
              type="text"
              name="search"
              id="search_term_main"
              class="form-control"
              placeholder="Cerca per titolo o autore..."
              value="{{ request('search') }}"
            />
          </div>
        </div>

        {{-- Pulsanti di ordinamento per data --}}
        <div class="col-auto">
          <div
            class="btn-group shadow-sm"
            role="group"
            aria-label="Ordina per data"
          >
            <a
              href="{{ route('admin.articles.index', array_merge(request()->query(), ['sort_direction' => 'desc', 'page' => 1])) }}"
              class="btn btn-outline-secondary {{ $currentSortDirection === 'desc' ? 'active' : '' }}"
              data-bs-toggle="tooltip"
              title="Dal più recente"
            >
              <i class="bi bi-sort-down"></i>
            </a>
            <a
              href="{{ route('admin.articles.index', array_merge(request()->query(), ['sort_direction' => 'asc', 'page' => 1])) }}"
              class="btn btn-outline-secondary {{ $currentSortDirection === 'asc' ? 'active' : '' }}"
              data-bs-toggle="tooltip"
              title="Dal meno recente"
            >
              <i class="bi bi-sort-up"></i>
            </a>
          </div>
        </div>

        {{-- Pulsante nascosto per submit --}}
        <button
          type="submit"
          class="visually-hidden"
          aria-hidden="true"
        >
          Cerca
        </button>
      </form>
    </div>

    {{-- Lista Articoli (contenuto della tabella rimane invariato) --}}
    <div class="article-list-container">
      @if ($articles->isNotEmpty())
        <div class="list-group list-group-flush">
          @foreach ($articles as $article)
            <div
              id="article-row-{{ $article->id }}"
              class="list-group-item p-3 article-admin-item-clickable"
              data-edit-url="{{ route('admin.articles.edit', $article) }}"
              style="cursor: pointer"
            >
              <div class="row g-3 align-items-center">
                <div class="col-auto">
                  <a
                    href="{{ route('admin.articles.edit', $article) }}"
                    tabindex="-1"
                    class="d-block"
                    style="width: 75px; height: 75px"
                  >
                    @php
                      $imageUrl = $article->image_url ?? asset('assets/img/placeholder_articolo.png');
                    @endphp

                    <img
                      src="{{ $imageUrl }}"
                      alt="Copertina: {{ Str::limit($article->title, 30) }}"
                      class="img-thumbnail object-fit-cover w-100 h-100 rounded"
                    />
                  </a>
                </div>

                <div class="col d-flex flex-column justify-content-center">
                  @if ($article->rubric)
                    <div class="mb-1">
                      <span
                        class="badge bg-light text-dark border me-1 fw-normal small"
                      >
                        {{ $article->rubric->name }}
                        {{-- MODIFICATO --}}
                      </span>
                    </div>
                  @endif

                  <h2 class="h6 fw-semibold mb-1 lh-sm">
                    <a
                      href="{{ route('admin.articles.edit', $article) }}"
                      class="text-dark text-decoration-none"
                    >
                      {{ $article->title }}
                    </a>
                  </h2>
                  <div class="small text-muted mb-2">
                    @if ($article->status === 'published' && $article->published_at)
                      Pubblicato:
                      {{ $article->published_at->isoFormat('D MMM YYYY, HH:mm') }}
                    @elseif ($article->status === 'scheduled' && $article->published_at)
                      Pianificato:
                      {{ $article->published_at->isoFormat('D MMM YYYY, HH:mm') }}
                    @elseif ($article->status === 'draft')
                      Bozza:
                      {{ $article->created_at->isoFormat('D MMM YYYY, HH:mm') }}
                    @elseif ($article->status === 'archived')
                      Archiviato:
                      {{ $article->updated_at->isoFormat('D MMM YYYY, HH:mm') }}
                    @endif
                    <span class="mx-1">•</span>
                    {{ $article->author->name ?? 'N/A' }}
                  </div>
                  <div class="small text-muted d-flex align-items-center">
                    <span
                      class="material-symbols-outlined fs-6 me-1 align-middle"
                      title="Mi Piace"
                    >
                      thumb_up
                    </span>
                    <span class="me-3">{{ $article->likes_count ?? 0 }}</span>
                    <span
                      class="material-symbols-outlined fs-6 me-1 align-middle"
                      title="Commenti"
                    >
                      chat_bubble
                    </span>
                    <span class="align-middle">
                      {{ $article->comments_count ?? 0 }}
                    </span>
                  </div>
                </div>

                <div
                  class="col-auto d-flex flex-column justify-content-center align-items-end article-actions-column"
                  style="gap: 0.5rem"
                >
                  @if ($article->status === 'published' || $article->status === 'scheduled')
                    <a
                      href="{{ route('blog.show', $article->slug) }}"
                      target="_blank"
                      class="btn btn-sm btn-outline-secondary py-1 px-2"
                      title="Visualizza articolo"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                    >
                      <span class="material-symbols-outlined fs-6 align-middle">
                        visibility
                      </span>
                    </a>
                  @else
                    <button
                      class="btn btn-sm btn-outline-secondary py-1 px-2 disabled"
                      title="Non visualizzabile"
                      data-bs-toggle="tooltip"
                      data-bs-placement="top"
                    >
                      <span class="material-symbols-outlined fs-6 align-middle">
                        visibility_off
                      </span>
                    </button>
                  @endif
                  <div class="dropdown">
                    <button
                      class="btn btn-sm btn-light py-1 px-2"
                      type="button"
                      id="articleActionsDropdown{{ $article->id }}"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                      title="Altre azioni"
                    >
                      <span class="material-symbols-outlined fs-6 align-middle">
                        more_vert
                      </span>
                    </button>
                    <ul
                      class="dropdown-menu dropdown-menu-end shadow-sm"
                      aria-labelledby="articleActionsDropdown{{ $article->id }}"
                    >
                      {{-- Dentro la ul.dropdown-menu --}}
                      <li>
                        <a
                          class="dropdown-item small d-flex align-items-center"
                          href="{{ route('admin.articles.edit', $article) }}"
                        >
                          <span class="material-symbols-outlined fs-6 me-2">
                            edit
                          </span>
                          Modifica
                        </a>
                      </li>

                      @if ($article->status === 'published')
                        <li>
                          <a
                            href="#"
                            class="dropdown-item small text-warning d-flex align-items-center"
                            onclick="confirmStatusChange(this, '{{ route('admin.articles.status.update', ['article' => $article->id, 'newStatus' => 'draft']) }}', 'Sei sicuro di voler annullare la pubblicazione? L\'articolo tornerà una bozza.'); return false;"
                          >
                            <span class="material-symbols-outlined fs-6 me-2">
                              unpublished
                            </span>
                            Annulla Pubblicazione
                          </a>
                        </li>
                      @elseif (in_array($article->status, ['draft', 'archived', 'scheduled']))
                        <li>
                          <a
                            href="#"
                            class="dropdown-item small text-success d-flex align-items-center"
                            onclick="confirmStatusChange(this, '{{ route('admin.articles.status.update', ['article' => $article->id, 'newStatus' => 'published']) }}', 'Sei sicuro di voler pubblicare ora questo articolo?'); return false;"
                          >
                            <span class="material-symbols-outlined fs-6 me-2">
                              publish
                            </span>
                            Pubblica Ora
                          </a>
                        </li>
                      @endif

                      @if ($article->status !== 'archived')
                        <li>
                          <a
                            href="#"
                            class="dropdown-item small d-flex align-items-center"
                            onclick="confirmStatusChange(this, '{{ route('admin.articles.status.update', ['article' => $article->id, 'newStatus' => 'archived']) }}', 'Sei sicuro di voler archiviare questo articolo?'); return false;"
                          >
                            <span class="material-symbols-outlined fs-6 me-2">
                              archive
                            </span>
                            Archivia
                          </a>
                        </li>
                      @else
                        <li>
                          <a
                            href="#"
                            class="dropdown-item small d-flex align-items-center"
                            onclick="confirmStatusChange(this, '{{ route('admin.articles.status.update', ['article' => $article->id, 'newStatus' => 'draft']) }}', 'Sei sicuro di voler ripristinare questo articolo come bozza?'); return false;"
                          >
                            <span class="material-symbols-outlined fs-6 me-2">
                              unarchive
                            </span>
                            Ripristina come Bozza
                          </a>
                        </li>
                      @endif
                      <li>
                        <hr class="dropdown-divider my-1" />
                      </li>
                      <li>
                        <button
                          type="button"
                          class="dropdown-item small text-danger d-flex align-items-center delete-article-btn"
                          data-article-id="{{ $article->id }}"
                          data-article-title="{{ addslashes(Str::limit($article->title, 50)) }}"
                          data-delete-url="{{ route('admin.articles.destroy', $article) }}"
                        >
                          <span class="material-symbols-outlined fs-6 me-2">
                            delete
                          </span>
                          Elimina
                        </button>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        @if ($articles->hasPages())
          <div class="mt-4 d-flex justify-content-center">
            {{ $articles->withQueryString()->links() }}
          </div>
        @endif
      @else
        <div class="alert alert-secondary text-center py-4">
          {{-- ... Messaggio "Nessun articolo trovato" ... (codice invariato) --}}
        </div>
      @endif
    </div>
  </div>

  {{-- Form nascosto per l'aggiornamento dello stato (invariato) --}}
  <form
    id="statusUpdateForm"
    method="POST"
    class="d-none"
  >
    @csrf
    @method('PATCH')
  </form>
</x-app-layout>
