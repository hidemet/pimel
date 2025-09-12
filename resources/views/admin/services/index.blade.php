<x-app-layout>
  @section('title', 'Gestione Servizi - Admin PIMEL')

  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <x-slot name="pageHeader">
    <x-layout.page-header title="Gestione Servizi">
      <a
        href="{{ route('admin.services.create') }}"
        class="btn btn-primary"
      >
        <span class="material-symbols-outlined fs-6 me-1 align-middle">
          add_circle
        </span>
        Nuovo Servizio
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
    @endif

    <div class="card shadow-sm rounded-3">
      <div class="table-responsive">
        <table
          class="table table-hover align-middle mb-0 rounded-3 overflow-hidden"
        >
          <thead class="table-dark">
            <tr>
              <th>Nome Servizio</th>
              <th>Categoria</th>
              <th class="text-end">Azioni</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($services as $service)
              <tr>
                <td>
                  <a
                    href="{{ route('admin.services.edit', $service) }}"
                    class="text-dark fw-semibold text-decoration-none"
                  >
                    {{ $service->name }}
                  </a>
                </td>
                <td>
                  <span
                    class="badge bg-secondary bg-opacity-25 text-secondary-emphasis rounded-pill"
                  >
                    {{ $service->targetCategory->name ?? 'N/A' }}
                  </span>
                </td>
                <td class="text-end">
                  <a
                    href="{{ route('admin.services.edit', $service) }}"
                    class="btn btn-sm btn-outline-primary me-1"
                    title="Modifica"
                  >
                    <span class="material-symbols-outlined fs-6 align-middle">
                      edit
                    </span>
                  </a>
                  <button
                    type="button"
                    class="btn btn-sm btn-outline-danger"
                    title="Elimina"
                    onclick="confirmDeletion({{ $service->id }}, '{{ addslashes($service->name) }}')"
                  >
                    <span class="material-symbols-outlined fs-6 align-middle">
                      delete
                    </span>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td
                  colspan="4"
                  class="text-center py-4 text-muted"
                >
                  Nessun servizio trovato.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if ($services->hasPages())
        <div class="card-footer">
          {{ $services->links() }}
        </div>
      @endif
    </div>
  </div>

  {{-- Form nascosto per l'eliminazione --}}
  <form
    id="deleteForm"
    method="POST"
    class="d-none"
  >
    @csrf
    @method('DELETE')
  </form>

  <script>
    function confirmDeletion(serviceId, serviceName) {
      if (
        confirm(
          `Sei sicuro di voler eliminare il servizio «${serviceName}»? L'azione è irreversibile.`
        )
      ) {
        const form = document.getElementById('deleteForm');
        form.action =
          '{{ route('admin.services.destroy', ':serviceId') }}'.replace(
            ':serviceId',
            serviceId
          );
        form.submit();
      }
    }
  </script>
</x-app-layout>
