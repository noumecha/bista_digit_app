$(function() {
    // toggle receivers - and filtering result
    const targetGroup = $('#target_group');
    const sendToAll = $('#sendToAll');
    const receiverSelector = $('#receiverSelector');
    // multiselect with search bar
    /*$('#receiver_ids').selectpicker({
        liveSearch: true,
        actionsBox: true,
        selectedTextFormat: 'count > 3',
        countSelectedText: '{0} utilisateurs sélectionnés',
        noneSelectedText: 'Sélectionner des utilisateurs',
        selectAllText: 'Tout sélectionner',
        deselectAllText: 'Tout désélectionner'
    });*/
    $('#create-notification-modal').on('shown.bs.modal', function () {
        $('#receiver_ids').select2({
            width : '100%',
            placeholder: "Choisir les utilisateurs",
            dropdownParent: $('#create-notification-modal')
        });
    });
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-notification-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var notificationId = $(this).data('notification-id');
        var actualiteIdInput = $('#notificationId');
        var form = $('#notificationForm');
        var button = $('#submit-notification-form-button');
        var header = $('#modal-notification-header');
        var headerText = $('#header-notification-text');
        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');
        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-notification-form-button-text').text('Envoyer');
            headerText.text('Creer une nouvelle notification');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-notification-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la notification');
            actualiteIdInput.val(notificationId);
            $.ajax({
                url: notificationId+"/edit",
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
    // When submiting form for updating or creating new actualite
    $(document).on('click','.spinner-submit-notification-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-notification-form-button-text');
        var notificationId = $('#notificationId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'update' + notificationId : 'save';
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
                fetchActus();
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
                    $('#create-notification-modal').hide();
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-notification-modal').on('hidden.bs.modal', function () {
        const form = $('#notificationForm');
        form.trigger('reset');
        $('#modal-notification-header').removeClass('bg-primary bg-success');
        $('#submit-notification-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-notification-form-buuton').children('span#submit-notification-form-button-text').text('');
    });

    // fetching actualites dynamically with filters
    $('#searchNotification, #groupFilter, #typeFilter').on('change keyup', function () {
        fetchActus();
    });

    // default data :
    fetchActus();

    // fetching all actualites :
    function fetchActus() {
        var formData = $('#filterNotificationForm').serialize();
        $.ajax({
            url : "/notifications/create",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#notificationsTable').html(data);
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
        fetchPage(page, '#notificationsTable');
    });

});