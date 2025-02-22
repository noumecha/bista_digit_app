$(function(){
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-evaluation-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var evaluationId = $(this).data('evaluation-id');
        var evaluationIdInput = $('#evaluationId');
        var form = $('#evaluationForm');
        var button = $('#submit-evaluation-form-button');
        var header = $('#modal-evaluation-header');
        var headerText = $('#header-evaluation-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-evaluation-form-button-text').text('Enregistrer');
            headerText.text('Creer un nouveau evaluation');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-evaluation-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les informations du evaluation');
            evaluationIdInput.val(evaluationId);
            $.ajax({
                url: "evaluations/"+evaluationId+"/edit",
                type: "GET",
                success: function(res) {
                    //fillInputForm(res, form);
                    fillInputForm(res, form);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    })

    // When submiting form for updating or creating new evaluation
    $(document).on('click','.spinner-submit-evaluation-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-evaluation-form-button-text');
        var evaluationId = $('#evaluationId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? 'evaluations/update/' + evaluationId : 'evaluations/save';
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
                fetchEvaluations();
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
    $('#create-evaluation-modal').on('hidden.bs.modal', function () {
        const form = $('#evaluationForm');
        form.trigger('reset');
        $('#modal-evaluation-header').removeClass('bg-primary bg-success');
        $('#submit-evaluation-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-evaluation-form-buuton').children('span#submit-evaluation-form-button-text').text('');
    });

    // fetching evaluations dynamically with filters
    $('#searchEvaluation,#trimestreFilter').on('change keyup', function () {
        fetchEvaluations();
    });

    // default data :
    fetchEvaluations();

    // fetching all evaluations :
    function fetchEvaluations() {
        var formData = $('#filterEvaluationForm').serialize();
        $.ajax({
            url : "/evaluation/evaluations",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#evaluationsTable').html(data);
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
        fetchPage(page, '#evaluationsTable');
    });

});