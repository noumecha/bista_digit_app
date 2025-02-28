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
        // doing nothing for input type = file
        if ($(this).is('input[type=file]')) return true;
        // for personnel function purpose
        if ($(this).attr('name') === 'fonction_id') {
            $(this).val(res.fonction_id);
            return true;
        }
        // for classe purpose
        if ($(this).attr('name') === 'classe_id') {
            if(res.classe_id)
                $(this).val(res.classe_id);
            else
                $(this).val(data.classe_id);
            return true;
        }
        // for ckeditor content
        if ($(this).attr('name') === 'content') {
            if(res.content && window.editor)
                window.editor.setData(res.content);
            return true;
        }
        // for coefficient purpose
        if($(this).attr('name') === 'coefficient') {
            if(res.coefficient)
                $(this).val(res.coefficient);
            return true;
        }
        // for discipline purpose :
        if($(this).attr('name') === 'user_id') {
            if(data.user_id) {
                $.get('discipline/student/' + data.user_id, function(data) {
                    data.forEach(student => {
                        $('#user_id').html(`<option value="${student.id}">${student.name}</option>`);
                    });
                });
            }
            return true;
        }
        // for matiere
        if($(this).attr('name') === 'groupe_matiere') {
            if(res.groupe_matiere)
                $(this).val(res.groupe_matiere);
            return true;
        }
        // for date inut type
        if ($(this).is('input[type=date]') && inputName in data) {
            const rawDate = data[inputName];
            if (rawDate) {
                $(this).val(rawDate.split(' ')[0]);
            }
            return true;
        }
        // for all default input type without constraints
        if (inputName in data) {
            if ($(this).is('input[type=checkbox]') || $(this).is('input[type=radio]')) {
                $(this).prop('checked', data[inputName]);
            } else {
                $(this).val(data[inputName]);
            }
        }
    });
    // only for questions and responses
    if (data.question || res.reponses) {
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

// function for enable or disable some input base on calcul
function enableDecision(totalAbs , totalJust, field) {
    // disabled by default
    $(field).hide();
    // lets calculate to enable or disable the field
    let absences = parseInt($(totalAbs).val()) || 0;
    let justifiees = parseInt($(totalJust).val()) || 0;
    let totalAbsences = absences - justifiees;
    if (totalAbsences > 40) {
        $(field).show();
    } else {
        $(field).hide();
    }
}
// function for formating date :
function formatDate(date) {
    return date.toISOString().split('T')[0];
}
// countdown section
function initializeCountdowns() {
    $('.countdown-timer').each(function() {
        var endDate = new Date($(this).data('end-date')).getTime();
        var startDate = new Date($(this).data('start-date')).getTime();
        var timerElement = $(this);

        var countdown = setInterval(function() {
            var now = new Date().getTime();
            var distance = endDate - now;

            if (distance <= 0) {
                clearInterval(countdown);
                timerElement.html(
                    "<span class='badge rounded-pill bg-danger'>terminé(e)</span>"
                );
                return;
            } else if (now < startDate) {
                distance = startDate - now;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                timerElement.html(
                    "<span class='badge rounded-pill bg-primary'> Commence dans : "+days + 'j '+ hours +
                    'h ' + minutes + 'm ' + seconds + 's'+ "</span>"
                );
            } else {
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                timerElement.html(
                    "<span class='badge rounded-pill bg-success'> Se termine dans : "+days + 'j '+ hours + 'h ' + minutes + 'm '
                    + seconds + 's'+ "</span>"
                );
            }
        }, 1000);
    });
}

function calculateEndDate(startDate, duration, weekInclude) {
    let endDateValue = new Date(startDate);
    let dayToAdd = 0;
    if (!duration || !startDate) return;
    while(dayToAdd < duration) {
        endDateValue.setDate(endDateValue.getDate() + 1);
        if (weekInclude || (endDateValue.getDay() !== 0 && endDateValue.getDay() !== 6)) {
            dayToAdd++;
        }
    }
    return endDateValue;
}

// Function to update the end date input
function updateEndDate(startDateInput, durationInput, weekInclude, endDateInput) {
    if (startDateInput.val() && durationInput.val()) {
        let endDate = calculateEndDate(
            startDateInput.val(),
            parseInt(durationInput.val()),
            weekInclude
        );
        endDateInput.val(formatDate(endDate));
    }
}