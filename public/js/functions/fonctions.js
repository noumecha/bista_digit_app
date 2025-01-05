$(function(){
    // setting up text on header or button depending of action
    $(document).on('click', '[data-bs-target="#create-fonction-modal"]', function(e) {
        e.preventDefault();
        var action = $(this).data('action');
        var fonctionId = $(this).data('fonction-id');
        var fonctionIdInput = $('#fonctionId');
        var form = $('#createEditFonctionForm');
        var button = $('#submit-fonction-form-button');
        var header = $('#modal-header');
        var headerText = $('#header-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-fonction-form-button-text').text('Enregistrer');
            headerText.text('Ajouter une nouvelle fonction');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-fonction-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la fonction');
            fonctionIdInput.val(fonctionId);
            $.ajax({
                url: "fonctions/"+fonctionId+"/edit",
                type: "GET",
                success: function(res) {
                    fillInputForm(res, form);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    })

    // When submitting create form for updating or creating new fonction
    $(document).on('click','.spinner-submit-fonction-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-fonction-form-button-text');
        var fonctionId = $('#fonctionId').val();
        var form_datas = $(this).closest('form').serialize();
        var form_method = buttonText.text() === 'Mettre à jour' ? 'PUT' : 'POST';
        var form_action = buttonText.text() === 'Mettre à jour' ? 'fonction/update/' + fonctionId : 'fonction/save';
        var modal_id = $(this).closest('div.modal').prop('id');
        $.ajax({
            url: form_action,
            type: form_method,
            data: form_datas,
            success: function(response) {
                if(response.error)
                    setSuccessMessage(response.error, '#modal-form-alert-errors');
                if(response.success)
                    setSuccessMessage(response.success, '#modal-form-alert-success');
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                fecthFonctions();
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
    $('#create-fonction-modal').on('hidden.bs.modal', function () {
        const form = $('#createEditFonctionForm');
        form.trigger('reset');
        $('#modal-header').removeClass('bg-primary bg-success');
        $('#submit-fonction-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-fonction-form-button').children('span#submit-fonction-form-button-text').text('');
    });

    // fetching note dynamically throw filters
    $('#searchFonction').on('change keyup', function () {
        fecthFonctions();
    });

    // default data :
    fecthFonctions();

    // fetching all notes :
    function fecthFonctions() {
        var formData = $('#filterFonctionForm').serialize();
        try {
            $.ajax({
                url : "fonctions",
                type : 'GET',
                data : formData,
                success : function(data) {
                    $('#fonctionsTable').html(data);
                },
                error: function(xhr, status, error) {
                    var datas = Object.entries(xhr.responseJSON.errors);
                    var errors = datas.map(error => error[1][0]);
                    setSuccessMessage(errors, '#modal-form-alert-errors');
                }
            });
        } catch (error) {
            console.log(error);
        }
    }

    /* success function
    function setSuccessMessage(msg, id) {
        var msgBlock = $(id);
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
        console.log(res);
        object = Object.keys(res)[0];
        data = res[object];
        form.find('input, select, checkbox').each(function() {
            var inputName = $(this).attr('name');
            if(inputName in data) {
                if ($(this).is('input[type=checkbox]') || $(this).is('input[type=radio]')) {
                    $(this).prop('checked', data[inputName]);
                } else if ($(this).is('select')) {
                    $(this).val(data[inputName]).trigger('change');
                } else {
                    $(this).val(data[inputName]);
                }
            }
        });
    }*/
});