$(function(){
    console.log($('#openDays').is(':checked'));
    // on change durree - update date fin
    $('#duree, #date_debut').on('input', function() {
        updateEndDate($('#date_debut'), $('#duree'), $('#openDays').is(':checked'), $('#date_fin'));
    });
    // Event listener for openDays checkbox
    $('#openDays').on('change', function() {
        updateEndDate($('#date_debut'), $('#duree'), $('#openDays').is(':checked'), $('#date_fin'));
    });
    // filtering remplissage dates base on the selected remplissage
    $('#evaluation_id').on('change', function() {
        let evalId = $(this).val();
        if (evalId) {
            $.get('remplissages/evalsdate/' + evalId, function(data) {
                var evalEndDate = new Date(data.dateDeFinEval);
                evalEndDate.setDate(evalEndDate.getDate() + parseInt(2));
                $('#date_debut').attr('min', formatDate(evalEndDate));
            });
        }
    });
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-remplissage-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var remplissageId = $(this).data('remplissage-id');
        var remplissageIdInput = $('#remplissageId');
        var form = $('#remplissageForm');
        var button = $('#submit-remplissage-form-button');
        var header = $('#modal-remplissage-header');
        var headerText = $('#header-remplissage-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-remplissage-form-button-text').text('Enregistrer');
            $('#date_fin').prop("readOnly", true);
            headerText.text('Creer un nouveau délai de remplissage des notes pour une séquence');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-remplissage-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour le délais de remplissage');
            remplissageIdInput.val(remplissageId);
            $('#date_fin').prop("readOnly", true);
            $('#evaluation_id').prop("disabled", true);
            $.ajax({
                url: "remplissages/"+remplissageId+"/edit",
                type: "GET",
                success: function(res) {
                    // filling form base on the data res
                    fillInputForm(res, form);
                    // Set date picker range based on evaluation dates
                    var endDate = new Date(res.dateDeFinEval);
                    endDate.setDate(endDate.getDate() + parseInt(2));
                    $('#date_debut').attr('min', formatDate(endDate));
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    })

    // When submiting form for updating or creating new remplissage
    $(document).on('click','.spinner-submit-remplissage-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-remplissage-form-button-text');
        var remplissageId = $('#remplissageId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? 'remplissages/update/' + remplissageId : 'remplissages/save';
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
                // reset form after creation
                if(formAction === 'remplissages/save') {
                    resetForm(form);
                }
                fetchRemplissages();
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
    $('#create-remplissage-modal').on('hidden.bs.modal', function () {
        const form = $('#remplissageForm');
        form.trigger('reset');
        $('#modal-remplissage-header').removeClass('bg-primary bg-success');
        $('#submit-remplissage-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-remplissage-form-buuton').children('span#submit-remplissage-form-button-text').text('');
        $('#evaluation_id').prop("disabled", false);
        $('#date_fin').prop("readOnly", false);
    });

    // fetching remplissages dynamically with filters
    $('#evaluationFilter,#statutFilter').on('change keyup', function () {
        fetchRemplissages();
    });

    // default data :
    fetchRemplissages();

    // fetching all remplissages :
    function fetchRemplissages() {
        var formData = $('#filterRemplissageForm').serialize();
        $.ajax({
            url : "/evaluation/remplissages",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#remplissagesTable').html(data);
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
        fetchPage(page, '#remplissagesTable');
    });

});