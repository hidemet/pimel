@push('page-scripts')
  @vite('resources/js/pages/admin-comments-index.js')
@endpush

<x-app-layout>
  @section('title', 'Moderazione Commenti - Admin PIMEL')

  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <div class="container py-4 admin-comments-page">
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
    <div class="nav nav-pills-segmented mb-4">
      <a
        href="{{ route('admin.comments.index', ['status' => 'pending']) }}"
        class="nav-link {{ $currentStatusFilter == 'pending' ? 'active' : '' }}"
      >
        In Attesa
      </a>
      <a
        href="{{ route('admin.comments.index', ['status' => 'approved']) }}"
        class="nav-link {{ $currentStatusFilter == 'approved' ? 'active' : '' }}"
      >
        Approvati
      </a>
    </div>

    {{-- Tabella Commenti --}}
    <div class="card shadow-sm rounded-3">
      <div class="table-responsive">
        @if ($comments->isNotEmpty())
          <table
            class="table table-hover align-middle mb-0 rounded-3 overflow-hidden"
          >
            <thead class="table-dark">
              <tr>
                <th style="width: 45%">Commento</th>
                <th style="width: 25%">Autore / Articolo</th>
                <th
                  class="text-center"
                  style="width: 10%"
                >
                  Data
                </th>
                <th
                  class="text-end"
                  style="width: 20%"
                >
                  Azioni
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($comments as $comment)
                <tr id="comment-row-{{ $comment->id }}">
                  <td>
                    <p class="mb-0 small">
                      {{ Str::limit($comment->body, 150) }}
                    </p>
                  </td>
                  <td>
                    <div class="fw-semibold">
                      {{ $comment->user->name ?? 'Utente eliminato' }}
                    </div>

                    @if ($comment->article)
                      <a
                        href="{{ route('blog.show', $comment->article->slug) }}#comment-{{ $comment->id }}"
                        target="_blank"
                        class="small text-muted text-decoration-none"
                      >
                        su: {{ Str::limit($comment->article->title, 30) }}
                        <i class="bi bi-box-arrow-up-right small"></i>
                      </a>
                    @else
                      <span class="small text-muted fst-italic">
                        Articolo eliminato
                      </span>
                    @endif
                  </td>
                  <td class="text-center small text-muted">
                    {{ $comment->created_at->isoFormat('D MMM YYYY') }}
                  </td>
                  <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                      @if (! $comment->is_approved)
                        <button
                          type="button"
                          class="btn btn-sm btn-outline-success comment-action-btn"
                          data-action="{{ route('admin.comments.update', $comment) }}"
                          data-method="PATCH"
                          data-payload='{"is_approved": 1}'
                          data-bs-toggle="tooltip"
                          title="Approva"
                        >
                          <i class="bi bi-check-lg"></i>
                        </button>
                      @else
                        <button
                          type="button"
                          class="btn btn-sm btn-outline-warning comment-action-btn"
                          data-action="{{ route('admin.comments.update', $comment) }}"
                          data-method="PATCH"
                          data-payload='{"is_approved": 0}'
                          data-bs-toggle="tooltip"
                          title="Sposta in 'In Attesa'"
                        >
                          <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                      @endif

                      <button
                        type="button"
                        class="btn btn-sm btn-outline-danger comment-action-btn"
                        data-action="{{ route('admin.comments.destroy', $comment) }}"
                        data-method="DELETE"
                        data-confirm="Sei sicuro di voler eliminare questo commento in modo permanente? L'azione è irreversibile."
                        data-bs-toggle="tooltip"
                        title="Elimina"
                      >
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="text-center p-5 text-muted">
            <i class="bi bi-chat-square-dots fs-1"></i>
            <p class="mt-3 mb-0">
              Nessun commento trovato per lo stato
              "{{ $currentStatusFilter == 'pending' ? 'In Attesa' : 'Approvati' }}".
            </p>
          </div>
        @endif
      </div>
      @if ($comments->hasPages())
        <div class="card-footer">
          {{ $comments->withQueryString()->links() }}
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
