import $ from 'jquery';

$(function () {
  const form = $('#contactForm');
  const submitBtn = $('#submitBtn');

  form.on('submit', function (event) {
    if (!this.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      // Se il form è valido, disabilita il pulsante e mostra lo stato di caricamento.
      submitBtn.prop('disabled', true);
      submitBtn.find('.button-text').addClass('d-none');
      submitBtn.find('.spinner-border, .loading-text').removeClass('d-none');
    }

    form.addClass('was-validated');
  });
});
