$(function(){
    $(document).on('click', '[data-bs-target="#create-modal"]', function(e) {
        var action = $(this).data('action');
        var form = $('#createEditForm');
        var button = $('#submit-form-button');
        var header = $('#modal-header');
        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.text('Enregistrer');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.text('Mettre à jour');
            $.ajax({
                url: $(this).attr('href'),
                type: 'GET',
                success: function (data) {
                    fillInputForm(data, form);
                },
                error: function (error) {
                    console.error('Erreur de recupération des données du personnel : ', error);
                },
            });
        }
    })

    // When submitting create form
    $(document).on('click','.spinner-submit-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        const action = $('#submit-form-button').text() === 'Mettre à jour' ? 'update' : 'create';
        var form_datas = $(this).closest('form').serialize();
        var form_method = action == "update" ? 'PUT' : 'POST'; //$(this).closest('form').prop('method');
        var form_action = $(this).closest('form').prop('action');
        var modal_id = $(this).closest('div.modal').prop('id');
        $.ajax({
            url: form_action,
            type: form_method,
            data: form_datas,
            success: function(response) {
                if(response.error) {
                    setSuccessMessage(response.error, '#modal-form-alert-errors');
                }
                if(response.success) {
                    setSuccessMessage(response.success, '#modal-form-alert-success');
                    setTimeout(() => location.reload(), 2000);
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
            },
            error: function(xhr) {
                var errors = []
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    stylingErrors(xhr.responseJSON.errors);
                    var datas = Object.entries(xhr.responseJSON.errors);
                    errors = datas.map(error => error[1][0]);
                    $('#'+modal_id).on('hidden.bs.modal', function() {
                        return false;
                    });
                } else {
                    setSuccessMessage('Erreur inconue' , '#modal-form-alert-errors');
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });

    // reseting data :
    $('#create-modal').on('hidden.bs.modal', function () {
        const form = $('#createEditForm');
        form.trigger('reset');
        $('#modal-header').removeClass('bg-primary bg-success');
        $('#submit-form-button').removeClass('btn-outline-primary btn-outline-success').text('');
    });

    // success function
    function setSuccessMessage(msg, id) {
        var msgBlock = $(id);
        console.log(msgBlock);
        msgBlock.stop(true, true);
        msgBlock.empty();
        if (Array.isArray(msg)) {
            var list = $('<ul></ul>');
            msg.forEach(function(m) {
                var items = $('<li></li>').text(m);
                list.append(items);
            });
            msgBlock.append(list);
        } else {
            msgBlock.append($('<p class="text-center"></p>').text(msg));
        }
        msgBlock.fadeIn().css('display', 'block');
        setTimeout(function() {
            msgBlock.fadeOut();
        }, 4000);
    }

    // stylise the error input
    function stylingErrors(errs) {
        $('input').removeClass('is-invalid');
        for(let field in errs) {
            if(errs.hasOwnProperty(field)) {
                let input_element = $('#' + field);
                if(input_element.length) {
                    input_element.addClass('is-invalid');
                    setTimeout(function() {
                        input_element.removeClass('is-invalid');
                    }, 4000);
                }
            }
        }
    }

    // fill the form with data :
    function fillInputForm(res, form) {
        object = Object.keys(res)[0];
        data = res[object];
        form.find('input, select, checkbox').each(function() {
            var inputName = $(this).attr('name');
            if(inputName in data) {
                if ($(this).is('input[type=checkbox]') || $(this).is('input[type=radio]')) {
                    $(this).prop('checked', data[inputName]);
                } else {
                    $(this).val(data[inputName]);
                }
            }
        });
    }
});