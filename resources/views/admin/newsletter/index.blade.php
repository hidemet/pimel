{{-- resources/views/admin/newsletter/index.blade.php --}}
<x-app-layout>
  @section('title', 'Iscritti Newsletter - Admin PIMEL')

  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

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

    {{-- Filtri per Stato --}}
    <div class="d-flex justify-content-start mb-4">
      <div class="btn-group shadow-sm">
        <a
          href="{{ route('admin.newsletter-subscriptions.index') }}"
          class="btn btn-sm {{ ! $statusFilter ? 'btn-primary' : 'btn-outline-secondary' }}"
        >
          Tutti
        </a>
        <a
          href="{{ route('admin.newsletter-subscriptions.index', ['status' => 'confirmed']) }}"
          class="btn btn-sm {{ $statusFilter === 'confirmed' ? 'btn-primary' : 'btn-outline-secondary' }}"
        >
          Confermati
        </a>
        <a
          href="{{ route('admin.newsletter-subscriptions.index', ['status' => 'pending']) }}"
          class="btn btn-sm {{ $statusFilter === 'pending' ? 'btn-primary' : 'btn-outline-secondary' }}"
        >
          In Attesa
        </a>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="table-responsive">
        @if ($subscriptions->isNotEmpty())
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 25%">Email</th>
                <th
                  class="text-center"
                  style="width: 15%"
                >
                  Stato
                </th>
                <th style="width: 40%">Preferenze (Rubriche)</th>
                <th
                  class="text-center"
                  style="width: 10%"
                >
                  Data
                </th>
                <th
                  class="text-end"
                  style="width: 10%"
                >
                  Azioni
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($subscriptions as $subscription)
                <tr>
                  <td>
                    <a
                      href="mailto:{{ $subscription->email }}"
                      class="text-dark fw-semibold text-decoration-none"
                    >
                      {{ $subscription->email }}
                    </a>
                  </td>
                  <td class="text-center">
                    @if ($subscription->confirmed_at)
                      <span
                        class="badge bg-success bg-opacity-10 text-success-emphasis rounded-pill"
                      >
                        Confermato
                      </span>
                    @else
                      <span
                        class="badge bg-warning bg-opacity-10 text-warning-emphasis rounded-pill"
                      >
                        In Attesa
                      </span>
                    @endif
                  </td>
                  <td>
                    @forelse ($subscription->rubrics as $rubric)
                      <span
                        class="badge bg-secondary bg-opacity-25 text-secondary-emphasis rounded-pill small fw-normal"
                      >
                        {{ $rubric->name }}
                      </span>
                    @empty
                      <span class="text-muted small fst-italic">
                        Nessuna preferenza specificata
                      </span>
                    @endforelse
                  </td>
                  <td class="text-center small text-muted">
                    {{ $subscription->created_at->isoFormat('D MMM YY') }}
                  </td>
                  <td class="text-end">
                    <a
                      href="{{ route('admin.newsletter-subscriptions.edit', $subscription) }}"
                      class="btn btn-sm btn-outline-primary"
                      data-bs-toggle="tooltip"
                      title="Modifica preferenze"
                    >
                      <i class="bi bi-pencil-fill"></i>
                    </a>

                    <form
                      action="{{ route('admin.newsletter-subscriptions.destroy', $subscription) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('Sei sicuro di voler disiscrivere questo utente?');"
                    >
                      @csrf
                      @method('DELETE')
                      <button
                        type="submit"
                        class="btn btn-sm btn-outline-danger"
                        data-bs-toggle="tooltip"
                        title="Disiscrivi utente"
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
            <i class="bi bi-person-x fs-1"></i>
            <p class="mt-3 mb-0">
              Nessun iscritto trovato per i filtri selezionati.
            </p>
          </div>
        @endif
      </div>
      @if ($subscriptions->hasPages())
        <div class="card-footer bg-light">
          {{ $subscriptions->withQueryString()->links() }}
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
