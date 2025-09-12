<x-app-layout>
  @section('title', 'Messaggi Ricevuti - Admin PIMEL')

  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <div class="container py-4">
    <div class="card shadow-sm rounded-3">
      <div class="table-responsive">
        @if ($messages->isNotEmpty())
          <table class="table table-hover align-middle mb-0 rounded-3 overflow-hidden">
            <thead class="table-dark">
              <tr>
                <th style="width: 35%">Mittente</th>
                <th style="width: 45%">Oggetto / Servizio</th>
                <th
                  class="text-center"
                  style="width: 20%"
                >
                  Data Invio
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($messages as $message)
                <tr
                  style="cursor: pointer"
                  class="contact-message-row"
                  data-bs-toggle="modal"
                  data-bs-target="#messageModal"
                  data-message-name="{{ $message->name }}"
                  data-message-email="{{ $message->email }}"
                  data-message-subject="{{ $message->subject ?: 'Nessun oggetto' }}"
                  data-message-service="{{ $message->service_of_interest ?: 'Non specificato' }}"
                  data-message-body="{{ $message->message }}"
                  data-message-date="{{ $message->created_at->isoFormat('D MMMM YYYY, HH:mm') }}"
                >
                  <td>
                    <div class="fw-semibold">{{ $message->name }}</div>
                    <a
                      href="mailto:{{ $message->email }}"
                      class="text-muted small text-decoration-none"
                      onclick="event.stopPropagation();"
                    >
                      {{ $message->email }}
                    </a>
                  </td>
                  <td>
                    <div class="fw-medium">
                      {{ $message->subject ?: 'Nessun oggetto' }}
                    </div>
                    @if ($message->service_of_interest)
                      <span
                        class="badge bg-secondary bg-opacity-25 text-secondary-emphasis rounded-pill small mt-1"
                      >
                        {{ $message->service_of_interest }}
                      </span>
                    @endif
                  </td>
                  <td class="text-center small text-muted">
                    {{ $message->created_at->isoFormat('D MMM YYYY') }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="text-center p-5 text-muted">
            <i class="bi bi-envelope-open fs-1"></i>
            <p class="mt-3 mb-0">Nessun messaggio ricevuto finora.</p>
          </div>
        @endif
      </div>
      @if ($messages->hasPages())
        <div class="card-footer">
          {{ $messages->links() }}
        </div>
      @endif
    </div>
  </div>

  <!-- Modale per visualizzare il messaggio completo -->
  <div
    class="modal fade"
    id="messageModal"
    tabindex="-1"
    aria-labelledby="messageModalLabel"
    aria-hidden="true"
  >
    <div
      class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"
    >
      <div class="modal-content">
        <div class="modal-header">
          <h5
            class="modal-title"
            id="messageModalLabel"
          >
            Dettagli Messaggio
          </h5>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <strong class="d-block">Da:</strong>
            <span id="modal-name"></span>
            (
            <a
              href="#"
              id="modal-email"
              class="text-decoration-none"
            ></a>
            )
          </div>
          <div class="mb-3">
            <strong class="d-block">Data:</strong>
            <span id="modal-date"></span>
          </div>
          <div class="mb-3">
            <strong class="d-block">Oggetto:</strong>
            <span id="modal-subject"></span>
          </div>
          <div class="mb-3">
            <strong class="d-block">Servizio di Interesse:</strong>
            <span id="modal-service"></span>
          </div>
          <hr />
          <strong class="d-block mb-2">Messaggio Completo:</strong>
          <div
            id="modal-message-body"
            class="p-3 bg-light rounded"
            style="white-space: pre-wrap"
          ></div>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Chiudi
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const messageModal = document.getElementById('messageModal');

      // Interfaccia Bootstrap in JavaScript vanilla
      messageModal.addEventListener('show.bs.modal', function (event) {
        const triggerElement = event.relatedTarget;
        const row = triggerElement.closest('tr.contact-message-row');

        if (!row) return;

        // Recupera i dati dagli attributi con JavaScript vanilla
        const name = row.getAttribute('data-message-name') || 'N/A';
        const email = row.getAttribute('data-message-email') || 'N/A';
        const subject = row.getAttribute('data-message-subject') || 'N/A';
        const service = row.getAttribute('data-message-service') || 'N/A';
        const body = row.getAttribute('data-message-body') || 'N/A';
        const date = row.getAttribute('data-message-date') || 'N/A';

        // Manipolazione DOM con jQuery (più pulita)
        $('#modal-name').text(name);
        $('#modal-email')
          .text(email)
          .attr('href', email !== 'N/A' ? 'mailto:' + email : '#');
        $('#modal-subject').text(subject);
        $('#modal-service').text(service);
        $('#modal-message-body').text(body);
        $('#modal-date').text(date);
      });
    });
  </script>
  </script>
</x-app-layout>
