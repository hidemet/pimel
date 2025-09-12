{{-- resources/views/admin/newsletter/index.blade.php --}}
<x-app-layout>
  @section('title', 'Admin PIMEL')

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
        @if ($subscriptions->isNotEmpty())
          <table
            class="table table-hover align-middle mb-0 rounded-3 overflow-hidden"
          >
            <thead class="table-dark">
              <tr>
                <th style="width: 35%">Email</th>
                <th style="width: 45%">Preferenze (Rubriche)</th>
                <th
                  class="text-center"
                  style="width: 20%"
                >
                  Data Iscrizione
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
                  <td>
                    @forelse ($subscription->rubrics as $rubric)
                      <span
                        class="badge bg-secondary bg-opacity-25 text-secondary-emphasis rounded-pill small fw-normal me-1"
                      >
                        {{ $rubric->name }}
                      </span>
                    @empty
                      <span class="text-muted small fst-italic">
                        Tutte le rubriche
                      </span>
                    @endforelse
                  </td>
                  <td class="text-center">
                    <small class="text-muted">
                      {{ $subscription->created_at->isoFormat('D MMM YY') }}
                    </small>
                    <br />
                    <small class="text-muted">
                      {{ $subscription->created_at->isoFormat('HH:mm') }}
                    </small>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="text-center p-5 text-muted">
            <p class="mt-3 mb-0">Nessun iscritto alla newsletter trovato.</p>
            <small class="text-muted">
              Gli utenti potranno iscriversi tramite il form nel sito.
            </small>
          </div>
        @endif
      </div>
      @if ($subscriptions->hasPages())
        <div class="card-footer bg-light">
          {{ $subscriptions->links() }}
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
