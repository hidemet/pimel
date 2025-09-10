// resources/js/admin-forms.js

import $ from 'jquery';
// Vite gestirà l'import di slugify dal node_modules.
import slugify from 'slugify';

// Attiva la logica solo quando il DOM è pronto.
$(function () {
  // Cerca un form che richiede questa funzionalità.
  const form = $('form.needs-slug-generation');

  // Se non c'è, interrompi l'esecuzione di questo script.
  if (form.length === 0) {
    return;
  }

  const nameInput = form.find('#name');
  const slugInput = form.find('#slug');
  const editSlugBtn = form.find('#editSlugBtn');
  let isSlugManuallyEdited = false;

  // Funzione helper per generare lo slug in modo consistente.
  function generateSlug(text) {
    return slugify(text, {
      lower: true,
      strict: true,
      remove: /[*+~.()'"!:@]/g,
      locale: 'it',
    });
  }

  // Funzione per aggiornare lo stato del campo slug (bloccato/sbloccato).
  function updateSlugLockState(isLocked) {
    if (isLocked) {
      isSlugManuallyEdited = true;
      slugInput.prop('readonly', false);
      editSlugBtn.html(
        '<span class="material-symbols-outlined fs-6">lock_open</span>'
      );
      editSlugBtn.attr('title', 'Lo slug è modificabile. Clicca per bloccare.');
    } else {
      isSlugManuallyEdited = false;
      slugInput.prop('readonly', true);
      editSlugBtn.html(
        '<span class="material-symbols-outlined fs-6">edit</span>'
      );
      editSlugBtn.attr('title', 'Modifica manualmente lo slug');
      slugInput.val(generateSlug(nameInput.val()));
    }
  }

  // Imposta lo stato iniziale al caricamento della pagina.
  if (slugInput.val().trim().length > 0 || slugInput.hasClass('is-invalid')) {
    updateSlugLockState(true); // Se c'è già uno slug o un errore, inizia come modificabile.
  } else {
    updateSlugLockState(false); // Altrimenti, inizia come automatico.
  }

  // Event listener per gli input dell'utente.
  nameInput.on('input', function () {
    if (!isSlugManuallyEdited) {
      slugInput.val(generateSlug($(this).val()));
    }
  });

  editSlugBtn.on('click', function () {
    updateSlugLockState(!isSlugManuallyEdited);
    if (isSlugManuallyEdited) {
      slugInput.focus();
    }
  });

  slugInput.on('input', function () {
    if (!isSlugManuallyEdited) {
      updateSlugLockState(true);
    }
  });
});
