$(function(){
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#update-userconfiguration-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var userId = $(this).data('userconfiguration-id');
        var appconfigurationIdInput = $('#userId');
        var action = userId !== "" ? "edit" : "create";
        var form = $('#userconfigurationForm');
        var button = $('#submit-userconfiguration-form-button');
        var header = $('#modal-userconfiguration-header');
        var headerText = $('#header-userconfiguration-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-userconfiguration-form-button-text').text('Enregistrer');
            headerText.text('Modifier vos informations');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-userconfiguration-form-button-text').text('Mettre à jour');
            headerText.text('Modifier vos informations');
            appconfigurationIdInput.val(userId);
            $.ajax({
                url: "profile/"+userId+"/edit",
                type: "GET",
                success: function(res) {
                    // filling form base on the data res
                    fillInputForm(res, form);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    })

    // When submiting form for updating or creating new remplissage
    $(document).on('click','.spinner-submit-userconfiguration-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-userconfiguration-form-button-text');
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? 'profile/update' : 'profile/create';
        var modalId = $(this).closest('div.modal').prop('id');
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
                console.log(formAction);
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                /*if (formAction === 'user_configuration/create') {
                    // close the form :
                    $('#'+modalId).hide();
                    $('.modal-backdrop').remove();
                }*/
                setTimeout(function() {
                    fetchAppConfigurations();
                }, 4000);
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

    // reseting form title and color when closing modal :
    $('#update-userconfiguration-modal').on('hidden.bs.modal', function () {
        const form = $('#userconfigurationForm');
        $('#modal-userconfiguration-header').removeClass('bg-primary bg-success');
        $('#submit-userconfiguration-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-userconfiguration-form-button').children('span#submit-userconfiguration-form-button-text').text('');
    });

    // fetching all remplissages :
    function fetchAppConfigurations() {
        $.ajax({
            url : "profile",
            type : 'GET',
            success : function(response) {
                console.log("User configuration loaded !");
                window.location.href = "profile";
            },
            error: function(xhr, status, error) {
                if (xhr && xhr.responseJSON.errors) {
                    var datas = Object.entries(xhr.responseJSON.errors);
                    var errors = datas.map(error => error[1][0]);
                    setSuccessMessage(errors, '#modal-form-alert-errors');
                }
            }
        });
    }
});