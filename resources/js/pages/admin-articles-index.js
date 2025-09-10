// resources/js/pages/admin-articles-index.js
import $ from 'jquery';

$(function () {
  console.log('Esecuzione script per admin/articles/index.');

  // La logica per i filtri avanzati è stata rimossa.
  // La ricerca e l'ordinamento ora sono gestiti da link e dal submit nativo del form.

  // --- LOGICA LISTA ARTICOLI ---
  $('.list-group').on(
    'click',
    '.article-admin-item-clickable',
    function (event) {
      if (
        $(event.target).closest(
          'button, a, .dropdown-menu, input, .article-actions-column'
        ).length
      ) {
        return;
      }
      window.location.href = $(this).data('edit-url');
    }
  );

  $('.list-group').on('click', '.delete-article-btn', function (e) {
    e.preventDefault();
    e.stopPropagation();
    const button = $(this);
    const deleteUrl = button.data('delete-url');
    const articleTitle = button.data('article-title');
    const articleId = button.data('article-id');

    if (
      confirm(`Sei sicuro di voler eliminare l'articolo «${articleTitle}»?`)
    ) {
      button.prop('disabled', true);
      $.ajax({
        url: deleteUrl,
        type: 'DELETE',
        dataType: 'json',
      })
        .done((response) => {
          if (response.success) {
            $('#article-row-' + articleId).fadeOut(400, function () {
              $(this).remove();
            });
            window.showToast(response.message, 'success');
          } else {
            window.showToast(response.message, 'error');
            button.prop('disabled', false);
          }
        })
        .fail((jqXHR) => {
          const errorMsg =
            jqXHR.responseJSON?.message || 'Errore di comunicazione.';
          window.showToast(errorMsg, 'error');
          button.prop('disabled', false);
        });
    }
  });

  window.confirmStatusChange = function (element, actionUrl, message) {
    if (confirm(message)) {
      $('#statusUpdateForm').attr('action', actionUrl).trigger('submit');
    }
  };
});
