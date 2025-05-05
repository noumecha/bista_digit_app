$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-specialite-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var specialiteId = $(this).data('specialite-id');
        var specialiteIdInput = $('#specialiteId');
        var form = $('#specialiteForm');
        var button = $('#submit-specialite-form-button');
        var header = $('#modal-specialite-header');
        var headerText = $('#header-specialite-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-specialite-form-button-text').text('Enregistrer');
            headerText.text('Ajouter une nouvelle specialite ou cycle');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-specialite-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les informations de la spécialité');
            specialiteIdInput.val(specialiteId);
            $.ajax({
                url: specialiteId+"/edit",
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
    // When submiting form for updating or creating new specialite
    $(document).on('click','.spinner-submit-specialite-form-button', function() {
        // ckeditor synchronize before save
        if (window.editor) {
            $('textarea#content').val(window.editor.getData());
        }
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-specialite-form-button-text');
        var specialiteId = $('#specialiteId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'update/' + specialiteId : 'save';
        var modalId = $(this).closest('div.modal').prop('id');
        if (buttonText.text() === 'Mettre à jour') {
            formData.append('_method', 'PUT');
        }
        $.ajax({
            url: formAction,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.error)
                    setSuccessMessage(response.error, '#modal-form-alert-errors');
                if(response.success)
                    setSuccessMessage(response.success, '#modal-form-alert-success');
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                if(formAction === 'save') {
                    resetForm(form);
                    setTimeout(function() {
                        window.editor.setData('');
                    }, 4000);
                }
                fetchSpecialites();
            },
            error: function(xhr) {
                var errors = []
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    stylingErrors(xhr.responseJSON.errors);
                    var datas = Object.entries(xhr.responseJSON.errors);
                    errors = datas.map(error => error[1][0]);
                    $('#'+modalId).on('hidden.bs.modal', function() {
                        return false;
                    });
                } else {
                    setSuccessMessage('Erreur inconue' , '#modal-form-alert-errors');
                    $('#create-specialite-modal').hide();
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-specialite-modal').on('hidden.bs.modal', function () {
        const form = $('#specialiteForm');
        form.trigger('reset');
        $('#modal-specialite-header').removeClass('bg-primary bg-success');
        $('#submit-specialite-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-specialite-form-button').children('span#submit-specialite-form-button-text').text('');
        window.editor.setData('');
    });

    // fetching specialites dynamically with filters
    $('#searchText').on('change keyup', function () {
        fetchSpecialites();
    });

    // default data :
    fetchSpecialites();

    // fetching all specialites :
    function fetchSpecialites() {
        var formData = $('#filterSpecialiteForm').serialize();
        $.ajax({
            url : "list",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#specialitesTable').html(data);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }
    // handle pagination :
    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        fetchPage(page, '#specialitesTable');
    });

});