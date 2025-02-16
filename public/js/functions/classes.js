$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-classe-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var classeId = $(this).data('classe-id');
        var classeIdInput = $('#classeId');
        var form = $('#classeForm');
        var button = $('#submit-classe-form-button');
        var header = $('#modal-classe-header');
        var headerText = $('#header-classe-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-classe-form-button-text').text('Enregistrer');
            headerText.text('Ajouter une nouvelle Classe');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-classe-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les information de la classe');
            classeIdInput.val(classeId);
            $.ajax({
                url: "classe/"+classeId+"/edit",
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
    $(document).on('click','.spinner-submit-classe-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-classe-form-button-text');
        var classeId = $('#classeId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'classe/update/' + classeId : 'classe/save';
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
                if(formAction === 'classe/save') {
                    resetForm(form);
                }
                fetchClasses();
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
    $('#create-classe-modal').on('hidden.bs.modal', function () {
        const form = $('#classeForm');
        form.trigger('reset');
        $('#modal-classe-header').removeClass('bg-primary bg-success');
        $('#submit-classe-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-classe-form-buuton').children('span#submit-classe-form-button-text').text('');
    });

    // fetching enseignements dynamically with filters
    $('#searchClasse,#cycleFilter,#sectionFilter').on('change keyup', function () {
        fetchClasses();
    });

    // default data :
    fetchClasses();

    // fetching all notes :
    function fetchClasses() {
        var formData = $('#filterClasseForm').serialize();
        $.ajax({
            url : "/education/classes",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#classesTable').html(data);
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
        fetchPage(page, '#classesTable');
    });
});