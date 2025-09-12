import $ from 'jquery';
import slugify from 'slugify';

$(function() {
    const form = $('form.needs-slug-generation');

    if (form.length === 0) {
        return;
    }

    // leggiamo l'id del campo sorgente dal data-attribute
    const sourceFieldId = form.data('slug-source-field');
    if (!sourceFieldId) {
        console.error('Attributo "data-slug-source-field" non trovato sul form.');
        return;
    }

    // usiamo l'id dinamico per trovare il campo corretto
    const sourceInput = form.find(`#${sourceFieldId}`);
    const slugInput = form.find('#slug');
    const editSlugBtn = form.find('#editSlugBtn');
    let isSlugManuallyEdited = false;

    // controlliamo che tutti gli elemeti esistano
    if (sourceInput.length === 0 || slugInput.length === 0) {
        console.error('Input sorgente o input slug non trovati nel form.');
        return;
    }

    function generateSlug(text) {
        // controllo per evitare errori se text è vuoto
        if (typeof text !== 'string') {
            return '';
        }
        return slugify(text, {
            lower: true,
            strict: true,
            remove: /[*+~.()'"!:@]/g,
            locale: 'it',
        });
    }

    function updateSlugLockState(isLocked) {
        if (isLocked) {
            isSlugManuallyEdited = true;
            slugInput.prop('readonly', false);
            editSlugBtn.html('<span class="material-symbols-outlined fs-6">lock_open</span>');
            editSlugBtn.attr('title', 'Lo slug è modificabile. Clicca per bloccare.');
        } else {
            isSlugManuallyEdited = false;
            slugInput.prop('readonly', true);
            editSlugBtn.html('<span class="material-symbols-outlined fs-6">edit</span>');
            editSlugBtn.attr('title', 'Modifica manually lo slug');
            slugInput.val(generateSlug(sourceInput.val()));
        }
    }

    if (slugInput.val().trim().length > 0 || slugInput.hasClass('is-invalid')) {
        updateSlugLockState(true);
    } else {
        updateSlugLockState(false);
    }

    sourceInput.on('input', function() {
        if (!isSlugManuallyEdited) {
            slugInput.val(generateSlug($(this).val()));
        }
    });

    editSlugBtn.on('click', function() {
        updateSlugLockState(!isSlugManuallyEdited);
        if (isSlugManuallyEdited) {
            slugInput.focus();
        }
    });

    slugInput.on('input', function() {
        if (!isSlugManuallyEdited) {
            updateSlugLockState(true);
        }
    });
});
