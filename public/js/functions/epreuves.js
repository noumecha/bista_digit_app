$(function(){
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-epreuve-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var epreuveId = $(this).data('epreuve-id');
        var epreuveIdInput = $('#epreuveId');
        var form = $('#epreuveForm');
        var button = $('#submit-epreuve-form-button');
        var header = $('#modal-epreuve-header');
        var headerText = $('#header-epreuve-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-epreuve-form-button-text').text('Enregistrer');
            $('#date_fin').prop("readOnly", true);
            headerText.text('Ajouter une nouvelle épreuve');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-epreuve-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les informations de l\'épreuve');
            epreuveIdInput.val(epreuveId);
            $.ajax({
                url: "remplissages/"+epreuveId+"/edit",
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

    // When submiting form for updating or creating new remplissage
    $(document).on('click','.spinner-submit-epreuve-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-epreuve-form-button-text');
        var epreuveId = $('#epreuveId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? 'remplissages/update/' + epreuveId : 'remplissages/save';
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
                fetchEpreuves();
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
    $('#create-epreuve-modal').on('hidden.bs.modal', function () {
        const form = $('#epreuveForm');
        form.trigger('reset');
        $('#modal-epreuve-header').removeClass('bg-primary bg-success');
        $('#submit-epreuve-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-epreuve-form-button').children('span#submit-epreuve-form-button-text').text('');
    });

    // fetching remplissages dynamically with filters
    $('#matiererFilter,#classeFilter,#typeEpreuveFilter,#searchText,#schoolYearFilter')
    .on('change keyup', function () {
        fetchEpreuves();
    });

    // default data :
    fetchEpreuves();

    // fetching all remplissages :
    function fetchEpreuves() {
        var formData = $('#filterRemplissageForm').serialize();
        $.ajax({
            url : "/evaluation/remplissages",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#epreuvesTable').html(data);
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
        fetchPage(page, '#epreuvesTable');
    });
});