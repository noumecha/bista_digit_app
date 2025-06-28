$(function(){
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-obcstats-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var statId = $(this).data('obcstats-id');
        var bulletinIdInput = $('#statId');
        var form = $('#obcstatsForm');
        var button = $('#submit-obcstats-form-button');
        var header = $('#modal-obcstats-header');
        var headerText = $('#header-obcstats-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-obcstats-form-button-text').text('Ajouter le classemnt');
            headerText.text('Ajouter un classement OBC pour une année spécifique');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-obcstats-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour le classement OBC pour l\'année');
            bulletinIdInput.val(statId);
            $('#annee_scolaire_id').prop("disabled", true);
            $.ajax({
                url: "obc/"+statId+"/edit",
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

    // When submiting form for updating or creating new obcstats
    $(document).on('click','.spinner-submit-obcstats-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-obcstats-form-button-text');
        var statId = $('#statId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? '/configuration/statistiques/obc/update/' + statId : '/configuration/statistiques/obc/save';
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
                if(response.error) {
                    setSuccessMessage(response.error, '#modal-form-alert-errors');
                }
                if(response.success) {
                    setSuccessMessage(response.success, '#modal-form-alert-success');
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                // reset form after creation
                if(formAction === '/configuration/statistiques/obc/save') {
                    resetForm(form);
                }
                fetchObcStats();
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
    $('#create-obcstats-modal').on('hidden.bs.modal', function () {
        const form = $('#obcstatsForm');
        form.trigger('reset');
        $('#modal-obcstats-header').removeClass('bg-primary bg-success');
        $('#submit-obcstats-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-obcstats-form-button').children('span#submit-obcstats-form-button-text').text('');
        $('#annee_scolaire_id').prop("disabled", false);
    });

    // fetching obc stats dynamically
    $('#trimestre_id,#classe_id,#yearFilter').on('change keyup', function () {
        fetchObcStats();
    });

    // default data :
    fetchObcStats();

    // fetching all bulletins :
    function fetchObcStats() {
        var formData = $('#filterObcStatsForm').serialize();
        $.ajax({
            url : "/configuration/statistiques/obc",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#obcStatsTable').html(data);
                initializeCountdowns();
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
        fetchPage(page, '#obcStatsTable');
    });
});