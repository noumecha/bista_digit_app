$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var personnelId = $(this).data('personnel-id');
        var yearId = $(this).data('year-id');
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
                url: "personnels/"+personnelId+"/edit/"+yearId,
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

    // When submiting form for updating or creating new personnel
    $(document).on('click','.spinner-submit-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-form-button-text');
        var personnelId = $('#personnelId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'personnel/update/' + personnelId : 'personnel/save';
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
                fetchPersonnels();
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
    $('#create-modal').on('hidden.bs.modal', function () {
        const form = $('#createEditForm');
        form.trigger('reset');
        $('#modal-header').removeClass('bg-primary bg-success');
        $('#submit-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-form-buuton').children('span#submit-form-button-text').text('');
    });

    // fetching personnels member dynamically with filters
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

    // handle pagination :
    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        fetchPage(page, '#personnelsTable');
    });
});