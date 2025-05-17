$(function(){
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#update-userpassword-modal"]', function(e) {
        e.preventDefault();
        // reseting the form
        var form = $('#userPasswordForm');
        form.trigger('reset');
    })

    // When submiting form for updating or creating new remplissage
    $(document).on('click','.spinner-submit-userpassword-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        // Kind of action
        var formAction = 'profile/password/update';
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
});