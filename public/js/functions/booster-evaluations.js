$(function(){
    // filtering trimestre dates base on the selected trimestre
    $('#trimestre_id').on('change', function() {
        let trimId = $(this).val();
        if (trimId) {
            $.get('/evaluation/evaluations/trimsdate/' + trimId, function(data) {
                var startDate = new Date(data.dateDeDebutTrim);
                var endDate = new Date(data.dateDeFinTrim);
                $('#dateDeDebut').attr('min', formatDate(startDate));
                $('#dateDeDebut').attr('max', formatDate(endDate));
                $('#dateDeFin').attr('min', formatDate(startDate));
                $('#dateDeFin').attr('max', formatDate(endDate));
            });
        }
    });
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-boosterevaluation-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var boosterEvaluationId = $(this).data('boosterevaluation-id');
        var boosterEvaluationIdInput = $('#boosterEvaluationId');
        var form = $('#boosterEvaluationForm');
        var button = $('#submit-boosterevaluation-form-button');
        var header = $('#modal-boosterevaluation-header');
        var headerText = $('#header-boosterevaluation-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-boosterevaluation-form-button-text').text('Enregistrer');
            headerText.text('Creer une nouvelle evaluation du programme booster');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-boosterevaluation-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les informations de l\'évalation');
            boosterEvaluationIdInput.val(boosterEvaluationId);
            $.ajax({
                url: "evaluations/"+boosterEvaluationId+"/edit",
                type: "GET",
                success: function(res) {
                    // filling form base on the data res
                    fillInputForm(res, form);
                    // Set date picker range based on trimester dates
                    var startDate = new Date(res.dateDeDebutTrim);
                    var endDate = new Date(res.dateDeFinTrim);
                    $('#dateDeDebut').attr('min', formatDate(startDate));
                    $('#dateDeDebut').attr('max', formatDate(endDate));
                    $('#dateDeFin').attr('min', formatDate(startDate));
                    $('#dateDeFin').attr('max', formatDate(endDate));
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    })

    // When submiting form for updating or creating new boosterevaluation
    $(document).on('click','.spinner-submit-boosterevaluation-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-boosterevaluation-form-button-text');
        var boosterEvaluationId = $('#boosterEvaluationId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? 'evaluations/update/' + boosterEvaluationId : 'evaluations/save';
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
                if(formAction === 'evaluations/save') {
                    resetForm(form);
                }
                fetchBoosterEvaluations();
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
    $('#create-boosterevaluation-modal').on('hidden.bs.modal', function () {
        const form = $('#boosterEvaluationForm');
        form.trigger('reset');
        $('#modal-boosterevaluation-header').removeClass('bg-primary bg-success');
        $('#submit-boosterevaluation-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-boosterevaluation-form-buuton').children('span#submit-boosterevaluation-form-button-text').text('');
    });

    // fetching evaluations dynamically with filters
    $('#searchEvaluation,#trimestreFilter,#statutFilter').on('change keyup', function () {
        fetchBoosterEvaluations();
    });

    // default data :
    fetchBoosterEvaluations();

    // fetching all evaluations :
    function fetchBoosterEvaluations() {
        var formData = $('#filterBoosterEvaluationForm').serialize();
        $.ajax({
            url : "evaluations",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#boosterEvaluationsTable').html(data);
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
        fetchPage(page, '#boosterEvaluationsTable');
    });

});