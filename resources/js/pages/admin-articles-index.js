import $ from 'jquery';

$(function () {
  const $tableBody = $('.table tbody');

  $tableBody.on('click', '.delete-article-btn', function () {
    const button = $(this);
    const deleteUrl = button.data('delete-url');
    const articleTitle = button.data('article-title');
    const row = button.closest('tr');

    if (
      !confirm(`Sei sicuro di voler eliminare l'articolo «${articleTitle}»?`)
    ) {
      return;
    }

    button.prop('disabled', true);
    const icon = button.find('.material-symbols-outlined');
    icon.text('hourglass_top');

    $.ajax({
      url: deleteUrl,
      type: 'DELETE',
      dataType: 'json', 
      success: function (response) {
        if (response.success) {
          row.fadeOut(400, function () {
            $(this).remove();
          });
          window.showToast(response.message, 'success', 'Successo');
        } else {
          // Caso in cui il server risponde 200 OK ma con success: false
          window.showToast(
            response.message || 'Operazione non riuscita.',
            'error',
            'Errore'
          );
          button.prop('disabled', false);
          icon.text('delete');
        }
      },
      error: function (jqXHR) {
        const errorMessage =
          jqXHR.responseJSON?.message ||
          'Si è verificato un errore di comunicazione.';
        window.showToast(errorMessage, 'error', 'Errore');
        button.prop('disabled', false);
        icon.text('delete');
      },
    });
  });
});
