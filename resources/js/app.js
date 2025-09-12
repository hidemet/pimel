import './bootstrap';
import $ from 'jquery';
import { Tooltip, Toast } from 'bootstrap';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/it';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Definizioni globali
window.$ = window.jQuery = $;
window.dayjs = dayjs;
dayjs.extend(relativeTime);
dayjs.locale('it');

// Setup globale AJAX per token CSRF
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
  },
});

// FUNZIONE HELPER GLOBALE per mostrare toast dinamici
window.showToast = function (message, type = 'info', title = 'Notifica') {
  const toastEl = document.getElementById('sessionToast');
  if (!toastEl) return;

  const toastHeader = toastEl.querySelector('.toast-header');
  const toastTitleEl = toastEl.querySelector('.toast-title');
  const toastBodyEl = toastEl.querySelector('.toast-body');
  const toastIconEl = toastEl.querySelector('.toast-icon');

  toastBodyEl.textContent = message;
  toastHeader.className = 'toast-header'; // Resetta le classi

  let headerClass = '';
  let iconHtml = '';
  switch (type) {
    case 'success':
      title = title === 'Notifica' ? 'Successo!' : title;
      headerClass = 'bg-success text-white';
      iconHtml = '<i class="bi bi-check-circle-fill"></i>';
      break;
    case 'error':
      title = title === 'Notifica' ? 'Errore!' : title;
      headerClass = 'bg-danger text-white';
      iconHtml = '<i class="bi bi-x-circle-fill"></i>';
      break;
    case 'warning':
      title = title === 'Notifica' ? 'Attenzione!' : title;
      headerClass = 'bg-warning text-dark';
      iconHtml = '<i class="bi bi-exclamation-triangle-fill"></i>';
      break;
    default:
      headerClass = 'bg-info text-white';
      iconHtml = '<i class="bi bi-info-circle-fill"></i>';
      break;
  }
  toastHeader.classList.add(...headerClass.split(' '));
  toastTitleEl.textContent = title;
  toastIconEl.innerHTML = iconHtml;

  const bsToast = Toast.getOrCreateInstance(toastEl);
  bsToast.show();
};


// ESECUZIONE CODICE AL "DOM READY"
$(function () {
  /**
   * Inizializzazione Globale dei Componenti Bootstrap
   */
  // 1. Inizializza tutti i tooltip
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new Tooltip(tooltipTriggerEl);
  });

  // 2. Controlla e mostra il toast di sessione
  const toastContainer = document.querySelector('.toast-container');
  if (toastContainer && toastContainer.dataset.sessionToast) {
    try {
      const toastData = JSON.parse(toastContainer.dataset.sessionToast);
      window.showToast(toastData.message, toastData.type, toastData.title);
    } catch (e) {
      console.error('Errore nel parsing dei dati del toast di sessione:', e);
    }
  }

  /**
   * Gestori di Eventi Globali
   */
  // Aggiornamento date con formato relativo
  $('.relative-time').each(function () {
    const dateString = $(this).data('date');
    if (dateString) {
      $(this).text(dayjs(dateString).fromNow());
      $(this).attr('title', dayjs(dateString).format('D MMMM YYYY, HH:mm'));
    }
  });

  // Gestione Asincrona del "Mi Piace" agli Articoli
  $(document).on('click', '.like-button', function (e) {
    e.preventDefault();
    const button = $(this);
    const articleId = button.data('article-id');
    const likesCountSpan = button.find('.like-count');
    const icon = button.find('i.bi');

    if (!articleId || button.prop('disabled')) return;
    button.prop('disabled', true);

    $.ajax({
      url: `/articles/${articleId}/like`,
      method: 'POST',
      dataType: 'json',
    })
      .done(function (data) {
        likesCountSpan.text(data.likes_count);
        button.toggleClass('text-primary text-muted', !data.liked);
        icon.toggleClass('bi-hand-thumbs-up-fill bi-hand-thumbs-up');
      })
      .fail(function (jqXHR) {
        if (jqXHR.status === 401) {
          const guestModal = new bootstrap.Modal(
            document.getElementById('guestActionModal')
          );
          guestModal.show();
        } else {
          window.showToast(
            'Si è verificato un errore. Riprova più tardi.',
            'error'
          );
        }
      })
      .always(function () {
        button.prop('disabled', false);
      });
  });

  /**
   * Router Javascript di Pagina
   */
  if ($('#article-list-container').length) import('./pages/blog-index.js');
  if ($('.admin-articles-page').length)
    import('./pages/admin-articles-index.js');
  if ($('.admin-comments-page').length)
    import('./pages/admin-comments-index.js');
  if ($('form.needs-slug-generation').length) import('./admin-forms.js');
  if ($('#serviceDetailModal').length) import('./pages/servizi-index.js');
  if ($('#article-content').length) import('./pages/blog-show.js');
});
