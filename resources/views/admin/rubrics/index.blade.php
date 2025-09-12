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

    {{-- Tabella Rubriche --}}
    <div class="card shadow-sm rounded-3">
      <div class="table-responsive">
        @if ($rubrics->isNotEmpty())
          <table
            class="table table-hover align-middle mb-0 rounded-3 overflow-hidden"
          >
            <thead class="table-dark">
              <tr>
                <th>Nome</th>
                <th class="text-end">Azioni</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($rubrics as $rubric)
                <tr>
                  <td>
                    <div
                      class="d-flex justify-content-between align-items-center"
                    >
                      <div>
                        <a
                          href="{{ route('blog.index', ['rubrica' => $rubric->slug]) }}"
                          class="text-dark fw-semibold text-decoration-none"
                          title="Visualizza articoli di questa rubrica nel blog"
                        >
                          {{ $rubric->name }}
                        </a>
                        <p class="small text-muted mb-0">
                          <code>{{ $rubric->slug }}</code>
                          @if ($rubric->articles_count > 0)
                              • {{ $rubric->articles_count }}
                              articol{{ $rubric->articles_count == 1 ? 'o' : 'i' }}
                          @endif
                        </p>
                      </div>
                    </div>
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
