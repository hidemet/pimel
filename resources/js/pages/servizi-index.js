// usato per popolare il modale dei servizi
$(function () {
  const $serviceDetailModal = $('#serviceDetailModal');
  if (!$serviceDetailModal.length) return;

  // Seleziona gli elementi della modale una sola volta
  const $modalTitle = $('#serviceDetailModalLabel');
  const $modalDescription = $('#modalServiceDescription');
  const $modalObjectives = $('#modalServiceObjectives');
  const $modalModalities = $('#modalServiceModalities');
  const $modalContactButton = $('#modalContactButton');

  const $descriptionSection = $('#modalDescriptionSection');
  const $objectivesSection = $('#modalObjectivesSection');
  const $modalitiesSection = $('#modalModalitiesSection');

  function nl2br(str) {
    if (typeof str !== 'string' || !str) return '';
    return str.replace(/(\r\n|\n\r|\r|\n)/g, '<br>');
  }

  $serviceDetailModal.on('show.bs.modal', function (event) {
    const $button = $(event.relatedTarget);
    if (!$button.length) return;

    const serviceName = $button.data('service-name') || 'Dettagli Servizio';
    const description = $button.data('service-description');
    const objectives = $button.data('service-objectives');
    const modalities = $button.data('service-modalities');
    const contactUrl = $button.data('service-contact-url');

    $modalTitle.text(serviceName);

    [
      {
        el: $modalDescription,
        section: $descriptionSection,
        content: description,
      },
      {
        el: $modalObjectives,
        section: $objectivesSection,
        content: objectives,
      },
      {
        el: $modalModalities,
        section: $modalitiesSection,
        content: modalities,
      },
    ].forEach((item) => {
      if (item.content && item.content.trim() !== '') {
        item.el.html(nl2br(item.content));
        item.section.show();
      } else {
        item.section.hide();
      }
    });

    if (contactUrl) {
      $modalContactButton.attr('href', contactUrl).show();
    } else {
      $modalContactButton.hide();
    }
  });
});
