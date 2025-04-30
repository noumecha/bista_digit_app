$(function(){
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#update-clubconfiguration-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var clubconfigurationId = $(this).data('clubconfiguration-id');
        var clubconfigurationIdInput = $('#clubconfigurationId');
        var action = clubconfigurationId !== "" ? "edit" : "create";
        var form = $('#clubconfigurationForm');
        var button = $('#submit-clubconfiguration-form-button');
        var header = $('#modal-clubconfiguration-header');
        var headerText = $('#header-clubconfiguration-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-clubconfiguration-form-button-text').text('Enregistrer');
            headerText.text('Modifier les informations du club');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-clubconfiguration-form-button-text').text('Mettre à jour');
            headerText.text('Modifier les informations du club');
            clubconfigurationIdInput.val(clubconfigurationId);
            $.ajax({
                url: "club_configuration/"+clubconfigurationId+"/edit",
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

    // When submiting form for updating club configuration
    $(document).on('click','.spinner-submit-clubconfiguration-form-button', function() {
        // ckeditor synchronize before save
        if (window.editor) {
            $('textarea#content').val(window.editor.getData());
        }
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-clubconfiguration-form-button-text');
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? 'club_configuration/update' : 'club_configuration/create';
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
                    fetchClubConfigurations();
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
    $('#update-clubconfiguration-modal').on('hidden.bs.modal', function () {
        const form = $('#clubconfigurationForm');
        form.trigger('reset');
        $('#modal-clubconfiguration-header').removeClass('bg-primary bg-success');
        $('#submit-clubconfiguration-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-clubconfiguration-form-button').children('span#submit-clubconfiguration-form-button-text').text('');
        window.editor.setData('');
    });

    // fetching all remplissages :
    function fetchClubConfigurations() {
        $.ajax({
            url : "club_configuration",
            type : 'GET',
            success : function(response) {
                console.log("Club configuration loaded!");
                window.location.href = "club_configuration";
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