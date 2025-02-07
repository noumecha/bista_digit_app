$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-matiere-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var matiereId = $(this).data('matiere-id');
        var matiereIdInput = $('#matiereId');
        var form = $('#matiereForm');
        var button = $('#submit-matiere-form-button');
        var header = $('#modal-matiere-header');
        var headerText = $('#header-matiere-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-matiere-form-button-text').text('Enregistrer');
            headerText.text('Ajouter une nouvelle matière');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-matiere-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la matière');
            matiereIdInput.val(matiereId);
            $.ajax({
                url: "matieres/"+matiereId+"/edit",
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

    // When submiting form for updating or creating subjects
    $(document).on('click','.spinner-submit-matiere-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-matiere-form-button-text');
        var matiereId = $('#matiereId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'matieres/update/' + matiereId : 'matieres/save';
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
                fetchMatiere();
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
    $('#create-matiere-modal').on('hidden.bs.modal', function () {
        const form = $('#matiereForm');
        form.trigger('reset');
        $('#modal-matiere-header').removeClass('bg-primary bg-success');
        $('#submit-matiere-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-matiere-form-buuton').children('span#submit-matiere-form-button-text').text('');
    });

    // fetching subjects dynamically with filters
    $('#searchMatiere').on('change keyup', function () {
        fetchMatiere();
    });

    // default data :
    fetchMatiere();

    // fetching all subjects :
    function fetchMatiere() {
        var formData = $('#filterMatiereForm').serialize();
        $.ajax({
            url : "/education/matieres",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#matieresTable').html(data);
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
        fetchPage(page, '#matieresTable');
    });
});