$(function(){
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-boosterstudent-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var boosterStudentId = $(this).data('boosterstudent-id');
        var boosterStudentIdInput = $('#boosterStudentId');
        var form = $('#boosterStudentForm');
        var button = $('#submit-boosterstudent-form-button');
        var header = $('#modal-boosterstudent-header');
        var headerText = $('#header-boosterstudent-text');
        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-boosterstudent-form-button-text').text('Enregistrer');
            headerText.text('Ajouter un élève au programme');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-boosterstudent-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la configuration');
            boosterStudentIdInput.val(boosterStudentId);
            $.ajax({
                url: "student/"+boosterStudentId+"/edit",
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
    // When submiting form for updating or creating new boosterstudent
    $(document).on('click','.spinner-submit-boosterstudent-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-boosterstudent-form-button-text');
        var boosterStudentId = $('#boosterStudentId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'students/update' + boosterStudentId : 'students/save';
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
                if(formAction === 'students/save') {
                    resetForm(form);
                    setTimeout(function() {
                        window.editor.setData('');
                    }, 4000);
                }
                fetchBoosterStudents();
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
                    $('#create-boosterstudent-modal').hide();
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-boosterstudent-modal').on('hidden.bs.modal', function () {
        const form = $('#boosterStudentForm');
        form.trigger('reset');
        $('#modal-boosterstudent-header').removeClass('bg-primary bg-success');
        $('#submit-boosterstudent-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-boosterstudent-form-button').children('span#submit-boosterstudent-form-button-text').text('');
    });
    // fetching boosterstudents dynamically with filters
    $('#searchText,#classeFilter').on('change keyup', function () {
        fetchBoosterStudents();
    });

    // default data :
    fetchBoosterStudents();

    // fetching all boosterstudents :
    function fetchBoosterStudents() {
        var formData = $('#filterBoosterStudentForm').serialize();
        $.ajax({
            url : "students",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#boosterStudentsTable').html(data);
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
        fetchPage(page, '#boosterStudentsTable');
    });

});