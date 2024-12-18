$(function(){
    // setting up text on header or button depending of action
    $(document).on('click', '[data-bs-target="#create-modal"]', function(e) {
        e.preventDefault();
        var action = $(this).data('action');
        var personnelId = $(this).data('personnel-id');
        var personnelIdInput = $('#personnelId');
        var personnelName = $(this).data('personnel-name');
        var form = $('#createEditForm');
        var button = $('#submit-form-button');
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
            button.children('span#submit-form-button-text').text('Enregistrer');
            headerText.text('Ajouter un nouveau membre du personnel');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les informations du personnel : ' + personnelName);
            personnelIdInput.val(personnelId);
            $.ajax({
                url: "personnels/"+personnelId+"/edit",
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

    // When submitting create form for updating or creating new personnel
    $(document).on('click','.spinner-submit-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-form-button-text');
        var personnelId = $('#personnelId').val();
        var form_datas = $(this).closest('form').serialize();
        var form_method = buttonText.text() === 'Mettre à jour' ? 'PUT' : 'POST';
        var form_action = buttonText.text() === 'Mettre à jour' ? 'personnel/update/' + personnelId : 'personnel/save';
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
                fetchPersonnels();
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
        $('#submit-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-form-buuton').children('span#submit-form-button-text').text('');
    });

    // fetching note dynamically throw filters
    $('#searchPersonnel,#funcFilter').on('change keyup', function () {
        fetchPersonnels();
    });

    // default data :
    fetchPersonnels();

    // fetching all notes :
    function fetchPersonnels() {
        var formData = $('#filterPersonnelForm').serialize();
        $.ajax({
            url : "personnels",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#personnelsTable').html(data);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }

    // success function
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
    }
});