// set the success message after form submission is successful
function setSuccessMessage(msg, id) {
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

// mark the input border of the form in red when some error occurs
function stylingErrors(errs) {
    $('input').removeClass('is-invalid');
    for (let field in errs) {
        if (errs.hasOwnProperty(field)) {
            if (window.editor && field === 'content') {
                const container = window.editor.ui.view.editable.element;
                console.log(container);
                container.classList.add('ck-editor-border-error');
                setTimeout(() => container.classList.remove('ck-editor-border-error'), 4000);
            } else if (field.startsWith('reponses.')) {
                const index = field.split('.')[1];
                const inputElement = $(`input[name="reponses[]"]`).eq(index);
                if (inputElement.length) {
                    inputElement.addClass('is-invalid');
                    setTimeout(() => inputElement.removeClass('is-invalid'), 4000);
                }
            } else {
                const inputElement = $('#' + field);
                if (inputElement.length) {
                    inputElement.addClass('is-invalid');
                    setTimeout(() => inputElement.removeClass('is-invalid'), 4000);
                }
            }
        }
    }
}

// fill inputs when the user clicks on the update button for tables lists
function fillInputForm(res, form) {
    const object = Object.keys(res)[0];
    const data = res[object];
    form.find('input, select, textarea, checkbox').each(function () {
        const inputName = $(this).attr('name');
        if ($(this).is('input[type=file]')) return true;

        if ($(this).attr('name') === 'fonction_id') {
            $(this).val(res.fonction_id);
            return true;
        }
        if ($(this).attr('name') === 'classe_id') {
            if(res.classe_id)
                $(this).val(res.classe_id);
            else
                $(this).val(data.classe_id);
            return true;
        }
        if ($(this).attr('name') === 'content') {
            if(res.content && window.editor)
                window.editor.setData(res.content);
            return true;
        }
        if($(this).attr('name') === 'coefficient') {
            if(res.coefficient)
                $(this).val(res.coefficient);
            return true;
        }

        if($(this).attr('name') === 'groupe_matiere') {
            if(res.groupe_matiere)
                $(this).val(res.groupe_matiere);
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
    // only for questions and responses
    form.find('textarea[name="question"]').val(data.question.question);
    $('#reponses-container').empty();
    reps = Object.entries(res.reponses);
    reps.forEach(([index, reponse]) => {
        var reponseHtml = `
            <div class="row reponse-item mt-2">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="reponses[]" value="${reponse.reponse}" class="form-control" placeholder="Entrez une réponse">
                    </div>
                </div>
                <div class="col-md-2 form-check">
                    <input type="hidden" name="status[]" class="form-check-input" value="${reponse.status}">
                    <input type="checkbox" id="formCheck" name="status_checkbox[]" ${reponse.status == 1 ? 'checked' : ''} value="1" class="form-check-input">
                    <label class="form-check-label" for="formCheck">Réponse correcte</label>
                </div>
                <div class="col-md-2">
                    <button type="button" id="remove-reponse-button" class="btn btn-danger remove-reponse-button">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#reponses-container').append(reponseHtml);
    });
}


// paginate throw different tables lists
function fetchPage(page, tableId) {
    $.ajax({
        url: "?page=" + page,
        type: "GET",
        success: function (data) {
            $(tableId).html(data);
        },
        error: function () {
            console.log("Pagination failed!");
        }
    });
}

// function for reseting form after submission validate
function resetForm(form) {
    setTimeout(function() {
        form.reset();
    }, 4000);
}