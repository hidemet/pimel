// resources/js/pages/blog-show.js

import $ from 'jquery';
import { Collapse } from 'bootstrap'; // Importiamo Collapse per la gestione delle risposte

$(function () {
  // --- Logica per il toggle delle icone nelle risposte (Refactoring in jQuery) ---
  const $replyContainers = $('.replies-container');

  $replyContainers.each(function () {
    const collapseElement = this;
    const collapseId = $(this).attr('id');
    // Selettore più specifico per evitare conflitti
    const $toggleButton = $(`button[data-bs-target="#${collapseId}"]`);
    const $icon = $toggleButton.find('.view-replies-icon');

    if ($toggleButton.length && $icon.length) {
      const updateIcon = () => {
        if ($(collapseElement).hasClass('show')) {
          $icon.text('arrow_drop_up');
        } else {
          $icon.text('arrow_drop_down');
        }
      };

      // Imposta stato iniziale e lega eventi
      updateIcon();
      $(collapseElement).on('show.bs.collapse hide.bs.collapse', updateIcon);
    }
  });

  // --- Logica per riaprire il form di risposta in caso di errore di validazione ---
  const errorBag = $('[data-error-reply-form]');
  if (errorBag.length) {
    const formId = errorBag.data('error-reply-form');
    const errorReplyForm = document.getElementById(formId); // L'uso di getElementById qui è pulito e diretto
    if (errorReplyForm) {
      const collapseInstance = Collapse.getOrCreateInstance(errorReplyForm);
      collapseInstance.show();
      // Scrolla verso il form con errore per migliorare l'usabilità
      errorReplyForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }
});
