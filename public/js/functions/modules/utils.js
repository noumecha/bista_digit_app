export function setSuccessMessage(msg, id) {
    const msgBlock = $(id);
    msgBlock.stop(true, true).empty();

    if (Array.isArray(msg)) {
        const list = $('<ul></ul>');
        msg.forEach((m) => list.append($('<li></li>').text(m)));
        msgBlock.append(list);
    } else {
        msgBlock.append($('<p class="text-center mb-0"></p>').text(msg));
    }

    msgBlock.fadeIn().css('display', 'block');
    setTimeout(() => msgBlock.fadeOut(), 4000);
}

export function stylingErrors(errs) {
    $('input').removeClass('is-invalid');
    for (let field in errs) {
        if (errs.hasOwnProperty(field)) {
            const inputElement = $('#' + field);
            if (inputElement.length) {
                inputElement.addClass('is-invalid');
                setTimeout(() => inputElement.removeClass('is-invalid'), 4000);
            }
        }
    }
}

export function fillInputForm(res, form) {
    const object = Object.keys(res)[0];
    const data = res[object];
    form.find('input, select, checkbox').each(function () {
        const inputName = $(this).attr('name');
        if ($(this).is('input[type=file]')) return true;

        if ($(this).attr('name') === 'fonction_id') {
            $(this).val(res.fonction_id);
            return true;
        }

        if ($(this).is('input[type=date]') && inputName in data) {
            const rawDate = data[inputName];
            if (rawDate) {
                $(this).val(rawDate.split(' ')[0]);
            }
            return true;
        }

        if (inputName in data) {
            if ($(this).is('input[type=checkbox]') || $(this).is('input[type=radio]')) {
                $(this).prop('checked', data[inputName]);
            } else {
                $(this).val(data[inputName]);
            }
        }
    });
}
