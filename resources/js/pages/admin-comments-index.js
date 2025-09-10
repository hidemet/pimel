// resources/js/pages/admin-comments-index.js

// Importiamo jQuery se intendiamo usarlo in modo esplicito (buona pratica)
import $ from 'jquery';

// Tutto il codice va dentro il wrapper "document ready" di jQuery.
$(function () {

  // Memorizza l'HTML originale dei pulsanti
  $('.comment-action-btn').each(function () {
    $(this).data('original-html', $(this).html());
  });

  // Gestore di eventi per i click sui pulsanti di azione
  $('.table-responsive').on('click', '.comment-action-btn', function (e) {
    e.preventDefault();

    const button = $(this);
    const actionUrl = button.data('action');
    const method = button.data('method');
    // NOTA: il payload JSON da un data-* attribute viene letto come stringa.
    // Dobbiamo convertirlo in un oggetto JavaScript.
    const payload = button.data('payload');
    const confirmMessage = button.data('confirm');
    const row = button.closest('tr');

    if (confirmMessage && !confirm(confirmMessage)) {
      return;
    }

    button
      .prop('disabled', true)
      .html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
      );
    row.find('.comment-action-btn').not(button).prop('disabled', true);

    $.ajax({
      url: actionUrl,
      method: method,
      // Qui passiamo direttamente l'oggetto payload.
      // jQuery lo gestirà correttamente per la richiesta.
      data: payload,
      dataType: 'json',
    })
      .done(function (response) {
        const tooltipInstance = Tooltip.getInstance(button[0]);
        if (tooltipInstance) tooltipInstance.dispose();

        row.css('background-color', '#d1e7dd').fadeOut(500, function () {
          $(this).remove();
          if ($('.table tbody tr').length === 0) {
            $('.table tbody').html(`
                        <tr id="empty-row"><td colspan="4" class="text-center p-5 text-muted">
                        <i class="bi bi-chat-square-dots fs-1"></i><p class="mt-3 mb-0">Nessun commento rimasto in questa sezione.</p>
                        </td></tr>`);
          }
        });

        // Usiamo la funzione globale definita in app.js
        window.showToast(response.message, 'success');
      })
      .fail(function (jqXHR) {
        console.error('AJAX Error:', jqXHR);
        row.find('.comment-action-btn').each(function () {
          $(this).prop('disabled', false).html($(this).data('original-html'));
        });
        row.css('background-color', '#f8d7da');
        const errorMessage =
          jqXHR.responseJSON?.message || 'Si è verificato un errore.';
        window.showToast(errorMessage, 'error', 'Errore');
      });
  });
});
