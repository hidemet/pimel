<x-app-layout>
  @section('title', 'Messaggi Ricevuti - Admin PIMEL')

  @isset($breadcrumbs)
    <div class="container pt-3">
      <x-admin.breadcrumb :items="$breadcrumbs" />
    </div>
  @endisset

  <div class="container py-4">
    <div class="card shadow-sm">
      <div class="table-responsive">
        @if ($messages->isNotEmpty())
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width: 25%">Mittente</th>
                <th style="width: 25%">Oggetto / Servizio</th>
                <th style="width: 35%">Anteprima Messaggio</th>
                <th
                  class="text-center"
                  style="width: 15%"
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
                  <td>
                    <p class="mb-0 small text-muted fst-italic">
                      "{{ Str::limit($message->message, 80) }}"
                    </p>
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
            id="modal-body"
            class="p-3 bg-light rounded"
            style="white-space: pre-wrap"
          ></div>
        </div>
        <div class="modal-footer">
          <a
            href="#"
            id="modal-reply-btn"
            class="btn btn-primary"
          >
            <i class="bi bi-reply-fill me-1"></i>
            Rispondi
          </a>
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

  @push('scripts')
    <script>
      // Esegui lo script quando il DOM è pronto, usando la sintassi jQuery.
      $(function () {
        // Seleziona l'elemento della modale una sola volta per efficienza.
        const messageModal = $('#messageModal');

        // Ascolta l'evento 'show.bs.modal' che Bootstrap emette prima di mostrare la modale.
        messageModal.on('show.bs.modal', function (event) {
          // event.relatedTarget è l'elemento che ha scatenato l'evento (la riga <tr> cliccata).
          const row = $(event.relatedTarget);

          // Recupera i dati dagli attributi data-* della riga.
          const name = row.data('message-name');
          const email = row.data('message-email');
          const subject = row.data('message-subject');
          const service = row.data('message-service');
          const body = row.data('message-body');
          const date = row.data('message-date');

          // Popola gli elementi all'interno della modale con i dati recuperati.
          messageModal.find('#modal-name').text(name);
          messageModal
            .find('#modal-email')
            .text(email)
            .attr('href', 'mailto:' + email);
          messageModal.find('#modal-subject').text(subject);
          messageModal.find('#modal-service').text(service);
          messageModal.find('#modal-body').text(body);
          messageModal.find('#modal-date').text(date);
          messageModal
            .find('#modal-reply-btn')
            .attr(
              'href',
              `mailto:${email}?subject=Re: ${encodeURIComponent(subject)}`
            );
        });
      });
    </script>
  @endpush
</x-app-layout>
