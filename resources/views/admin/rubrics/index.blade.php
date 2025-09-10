{{-- resources/views/admin/rubrics/index.blade.php --}}
<x-app-layout>
  @section('title', 'Gestione Rubriche - Admin PIMEL')

  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <x-slot name="pageHeader">
    <x-layout.page-header title="Gestione Rubriche">
      <a
        href="{{ route('admin.rubrics.create') }}"
        class="btn btn-primary"
      >
        <span class="material-symbols-outlined fs-6 me-1 align-middle">
          add_circle
        </span>
        Nuova Rubrica
      </a>
    </x-layout.page-header>
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
    @elseif (session('error'))
      <div
        class="alert alert-danger alert-dismissible fade show"
        role="alert"
      >
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="alert"
          aria-label="Close"
        ></button>
      </div>
    @endif

    {{-- Riga Ricerca e Ordinamento Semplificata --}}
    <div class="mb-4">
      <form
        id="filterSortForm"
        action="{{ route('admin.rubrics.index') }}"
        method="GET"
        class="row g-2 align-items-center"
      >
        {{-- Campo di ricerca --}}
        <div class="col-sm-8 col-md-9">
          <label
            for="search_term_main"
            class="visually-hidden"
          >
            Cerca Rubriche
          </label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input
              type="text"
              name="search"
              id="search_term_main"
              class="form-control"
              placeholder="Cerca per nome o descrizione..."
              value="{{ request('search') }}"
            />
          </div>
        </div>

        {{-- Pulsanti di ordinamento --}}
        <div class="col-sm-4 col-md-3 d-flex justify-content-end">
          <div
            class="btn-group shadow-sm"
            role="group"
            aria-label="Ordina per nome"
          >
            <a
              href="{{ route('admin.rubrics.index', array_merge(request()->query(), ['sort_direction' => 'asc'])) }}"
              class="btn btn-outline-secondary {{ $currentSort === 'asc' ? 'active' : '' }}"
              data-bs-toggle="tooltip"
              title="Ordina A-Z"
            >
              <i class="bi bi-sort-alpha-down"></i>
            </a>
            <a
              href="{{ route('admin.rubrics.index', array_merge(request()->query(), ['sort_direction' => 'desc'])) }}"
              class="btn btn-outline-secondary {{ $currentSort === 'desc' ? 'active' : '' }}"
              data-bs-toggle="tooltip"
              title="Ordina Z-A"
            >
              <i class="bi bi-sort-alpha-up"></i>
            </a>
          </div>
        </div>

        {{-- Pulsante nascosto per l'invio del form tramite tasto "Invio" --}}
        <button
          type="submit"
          class="visually-hidden"
          aria-hidden="true"
        >
          Cerca
        </button>
      </form>
    </div>

    {{-- Tabella Rubriche --}}
    <div class="card shadow-sm">
      <div class="table-responsive">
        @if ($rubrics->isNotEmpty())
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Nome</th>
                <th class="d-none d-md-table-cell">Descrizione</th>
                <th class="text-center">Articoli</th>
                <th class="text-end">Azioni</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($rubrics as $rubric)
                <tr>
                  <td>
                    <a
                      href="{{ route('admin.rubrics.edit', $rubric) }}"
                      class="text-dark fw-semibold text-decoration-none"
                    >
                      {{ $rubric->name }}
                    </a>
                    <p class="small text-muted mb-0 d-none d-lg-block">
                      <code>{{ $rubric->slug }}</code>
                    </p>
                  </td>
                  <td class="d-none d-md-table-cell text-muted small">
                    {{ Str::limit($rubric->description ?? 'Nessuna descrizione.', 100) }}
                  </td>
                  <td class="text-center">
                    @if ($rubric->articles_count > 0)
                      <a
                        href="{{ route('admin.articles.index', ['rubric_id' => $rubric->id]) }}"
                        class="btn btn-sm btn-light position-relative"
                      >
                        Articoli
                        <span
                          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary"
                        >
                          {{ $rubric->articles_count }}
                        </span>
                      </a>
                    @else
                      <span class="text-muted">0</span>
                    @endif
                  </td>
                  <td class="text-end">
                    <a
                      href="{{ route('admin.rubrics.edit', $rubric) }}"
                      class="btn btn-sm btn-outline-primary"
                      title="Modifica Rubrica"
                    >
                      <i class="bi bi-pencil-fill"></i>
                    </a>
                    <form
                      action="{{ route('admin.rubrics.destroy', $rubric) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Sei sicuro di voler eliminare la rubrica «{{ addslashes($rubric->name) }}»? L\'azione è irreversibile e disassocierà la rubrica da tutti gli articoli.');"
                    >
                      @csrf
                      @method('DELETE')
                      <button
                        type="submit"
                        class="btn btn-sm btn-outline-danger"
                        title="Elimina Rubrica"
                      >
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="text-center p-5 text-muted">
            <i class="bi bi-folder-x fs-1"></i>
            <p class="mt-3 mb-0">Nessuna rubrica trovata.</p>
            @if (request('search'))
              <p class="mt-2">
                <a
                  href="{{ route('admin.rubrics.index') }}"
                  class="btn btn-sm btn-outline-secondary"
                >
                  Rimuovi filtro di ricerca
                </a>
              </p>
            @endif
          </div>
        @endif
      </div>

      {{-- Paginazione --}}
      @if ($rubrics->hasPages())
        <div class="card-footer bg-light">
          {{ $rubrics->withQueryString()->links() }}
        </div>
      @endif
    </div>
  </div>

  @push('scripts')
    <script>
      // Inizializza i tooltip di Bootstrap (se non già fatto globalmente)
      document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(
          document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl);
        });
      });
    </script>
  @endpush
</x-app-layout>
