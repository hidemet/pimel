import $ from 'jquery';
import { Tooltip } from 'bootstrap';

$(function () {
  // Inizializza i tooltip al caricamento della pagina
  $('[data-bs-toggle="tooltip"]').each(function () {
    new Tooltip(this);
  });

  // Usa la delega degli eventi per i pulsanti di azione
  $('.table-responsive').on('click', '.comment-action-btn', function () {
    const button = $(this);
    const actionUrl = button.data('action');
    const method = button.data('method');
    const payload = button.data('payload');
    const confirmMessage = button.data('confirm');

    // Chiedi conferma se necessario
    if (confirmMessage && !confirm(confirmMessage)) {
      return;
    }

    // Disabilita il pulsante per prevenire doppi click
    button.prop('disabled', true);

    // Nascondi il tooltip per evitare che rimanga "appeso"
    const tooltipInstance = Tooltip.getInstance(button[0]);
    if (tooltipInstance) {
      tooltipInstance.hide();
    }

    $.ajax({
      url: actionUrl,
      method: method,
      data: payload,
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
      },
    })
      .done(function (response) {
        window.showToast(response.message, 'success', 'Successo');
        // Ricarica la pagina per mostrare lo stato aggiornato
        window.location.reload();
      })
      .fail(function (jqXHR) {
        const errorMessage =
          jqXHR.responseJSON?.message || 'Si è verificato un errore.';
        window.showToast(errorMessage, 'error', 'Errore');
        // Riabilita il pulsante solo in caso di errore, così l'utente può riprovare
        button.prop('disabled', false);
      });
  });
});
