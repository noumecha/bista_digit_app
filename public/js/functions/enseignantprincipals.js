$(function(){
    // filtering teachers base on classe id
    $('#classe_id').on('change', function() {
        let classeId = $(this).val();
        $('#user_id').html('<option value="">Veuillez selectionnez un enseignant</option>');
        if (classeId) {
            $.get('/enseignantprincipals/teachers/' + classeId, function(data) {
                data.forEach(teacher => {
                    $('#user_id').append(`<option value="${teacher.id}">${teacher.name}</option>`);
                });
            });
        }
    });
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-enseignantprincipal-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var enseignantPrincipalId = $(this).data('enseignantprincipal-id');
        var yearId = $(this).data('year-id');
        var enseignantPrincipalIdInput = $('#enseignantPrincipalId');
        var form = $('#enseignantPrincipalForm');
        var button = $('#submit-enseignantprincipal-form-button');
        var header = $('#modal-enseignantprincipal-header');
        var headerText = $('#header-enseignantprincipal-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-enseignantprincipal-form-button-text').text('Enregistrer');
            headerText.text('Définir un enseignant principal pour une classe');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-enseignantprincipal-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la configuration de l\'enseignant principal');
            enseignantPrincipalIdInput.val(enseignantPrincipalId);
            $.ajax({
                url: "enseignantprincipal/"+enseignantPrincipalId+"/edit/"+yearId,
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

    // When submiting form for updating or creating principal class teacher
    $(document).on('click','.spinner-submit-enseignantprincipal-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-enseignantprincipal-form-button-text');
        var enseignantPrincipalId = $('#enseignantPrincipalId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'enseignantprincipal/update/' + enseignantPrincipalId : 'enseignantprincipal/save';
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
                if(formAction === 'enseignantprincipal/save') {
                    resetForm(form);
                }
                fetchEnseignantPrincipals();
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
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-enseignantprincipal-modal').on('hidden.bs.modal', function () {
        const form = $('#enseignantPrincipalForm');
        form.trigger('reset');
        $('#modal-enseignantprincipal-header').removeClass('bg-primary bg-success');
        $('#submit-enseignantprincipal-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-enseignantprincipal-form-buuton').children('span#submit-enseignantprincipal-form-button-text').text('');
    });

    // fetching enseignements dynamically with filters
    $('#searchTeacher,#classeFilter').on('change keyup', function () {
        fetchEnseignantPrincipals();
    });

    // default data :
    fetchEnseignantPrincipals();

    // fetching all notes :
    function fetchEnseignantPrincipals() {
        var formData = $('#filterEnseignantPrincipalForm').serialize();
        $.ajax({
            url : "/education/enseignantprincipal",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#enseignantPrincipalsTable').html(data);
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
        fetchPage(page, '#enseignantPrincipalsTable');
    });

});