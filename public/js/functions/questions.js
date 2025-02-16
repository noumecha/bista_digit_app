$(function(){
    // Manage responses dynamically
    let reponseId = 0;
    $(document).on('click', '#add-reponse-button', function () {
        reponseId++;
        var reponseHtml = `
            <div class="row reponse-item mt-2" id="reponse-${reponseId}">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" id="responses[]" name="reponses[]" class="form-control" placeholder="Entrez une réponse">
                    </div>
                </div>
                <div class="col-md-2 form-check">
                    <input type="hidden" name="status[]" class="form-check-input" value="0">
                    <input type="checkbox" id="formCheck" name="status_checkbox[]" value="1" class="form-check-input">
                    <label class="form-check-label" for="formCheck">Réponse correcte</label>
                </div>
                <div class="col-md-2">
                    <button type="button" id="remove-reponse-button" class="btn btn-danger remove-reponse-button">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#reponses-container').append(reponseHtml);
    });

    // delete reponse from dom
    $(document).on('click', '.remove-reponse-button', function () {
        $(this).closest('.reponse-item').remove();
    });

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-question-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var questionId = $(this).data('question-id');
        var questionIdInput = $('#questionId');
        var form = $('#questionForm');
        var button = $('#submit-question-form-button');
        var header = $('#modal-question-header');
        var headerText = $('#header-question-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-question-form-button-text').text('Enregistrer');
            headerText.text('Creer un nouveau question (QCM)');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-question-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la configuration de la question');
            questionIdInput.val(questionId);
            $.ajax({
                url: "questions/"+questionId+"/edit",
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

    // When submiting form for updating or creating new question
    $(document).on('click','.spinner-submit-question-form-button', function() {
        // Synchronize responses status checkbox
        $('input[name="status_checkbox[]"]').each(function (index, checkbox) {
            if ($(checkbox).is(':checked')) {
                $(checkbox).siblings('input[name="status[]"]').val(1);
            }
        });
        // ckeditor synchronize before save
        if (window.editor) {
            $('textarea#content').val(window.editor.getData());
        }
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-question-form-button-text');
        var questionId = $('#questionId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? 'questions/update/' + questionId : 'questions/save';
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
                if(formAction === 'questions/save') {
                    resetForm(form);
                    setTimeout(function() {
                        window.editor.setData('');
                        $('.reponse-item').remove();
                    }, 4000);
                }
                fetchQuestions();
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
    $('#create-question-modal').on('hidden.bs.modal', function () {
        const form = $('#questionForm');
        form.trigger('reset');
        $('#modal-question-header').removeClass('bg-primary bg-success');
        $('#submit-question-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-question-form-buuton').children('span#submit-question-form-button-text').text('');
        // clear the editor after submit the form with success
        window.editor.setData('');
        // remove item on dom after closing the form :
        $('.reponse-item').remove();
    });

    // fetching questions dynamically with filters
    $('#searchQuestion,#devoirFilter').on('change keyup', function () {
        fetchQuestions();
    });

    // default data :
    fetchQuestions();

    // fetching all questions :
    function fetchQuestions() {
        var formData = $('#filterQuestionForm').serialize();
        $.ajax({
            url : "/education/questions",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#questionsTable').html(data);
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
        fetchPage(page, '#questionsTable');
    });

});