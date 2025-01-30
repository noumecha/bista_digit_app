$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-enseignement-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var enseignementId = $(this).data('enseignement-id');
        var yearId = $(this).data('year-id');
        var enseignementIdInput = $('#enseignementId');
        var form = $('#enseignementForm');
        var button = $('#submit-enseignement-form-button');
        var header = $('#modal-enseignement-header');
        var headerText = $('#header-enseignement-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-enseignement-form-button-text').text('Enregistrer');
            headerText.text('Attribuer une classe à un enseignant en fonction de sa matière');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-enseignement-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la configuration de l\'enseignement');
            enseignementIdInput.val(enseignementId);
            $.ajax({
                url: "enseignement/"+enseignementId+"/edit/"+yearId,
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

    // When submiting form for updating or creating new personnel
    $(document).on('click','.spinner-submit-enseignement-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-enseignement-form-button-text');
        var enseignementId = $('#enseigmentId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'enseignement/update/' + enseignementId : 'enseignement/save';
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
                fetchEnseignements();
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
    $('#create-enseignement-modal').on('hidden.bs.modal', function () {
        const form = $('#enseignementForm');
        form.trigger('reset');
        $('#modal-enseignement-header').removeClass('bg-primary bg-success');
        $('#submit-enseignement-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-enseignement-form-buuton').children('span#submit-enseignement-form-button-text').text('');
    });

    // fetching enseignements dynamically with filters
    $('#searchTeacher,#classeFilter,#matiereFilter').on('change keyup', function () {
        fetchEnseignements();
    });

    // default data :
    fetchEnseignements();

    // fetching all notes :
    function fetchEnseignements() {
        var formData = $('#filterEnseignementForm').serialize();
        $.ajax({
            url : "/education/enseignement",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#enseignementsTable').html(data);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }
});