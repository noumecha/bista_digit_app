$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-teacherSubject-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var teacherSubjectId = $(this).data('teacherSubject-id');
        var yearId = $(this).data('year-id');
        var teacherSubjectIdInput = $('#teacherSubjectId');
        var form = $('#teacherSubjectForm');
        var button = $('#submit-teacherSubject-form-button');
        var header = $('#modal-teacherSubject-header');
        var headerText = $('#header-teacherSubject-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-teacherSubject-form-button-text').text('Enregistrer');
            headerText.text('Ajouter une nouvelle configuration');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-teacherSubject-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les configurations');
            teacherSubjectIdInput.val(teacherSubjectId);
            $.ajax({
                url: "enseignantMatiere/"+ teacherSubjectId + "/edit/" + yearId,
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

    // When submiting form for updating or creating new teacherSubject spinner-submit-year-form-button
    $(document).on('click','.spinner-submit-teacherSubject-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-teacherSubject-form-button-text');
        var teacherSubjectId = $('#teacherSubjectId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? '/enseignantMatiere/update/' + teacherSubjectId : '/enseignantMatiere/save';
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
                fetchteacherSubjects();
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
    $('#create-teacherSubject-modal').on('hidden.bs.modal', function () {
        const form = $('#teacherSubjectForm');
        form.trigger('reset');
        $('#modal-teacherSubject-header').removeClass('bg-primary bg-success');
        $('#submit-teacherSubject-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-teacherSubject-form-button').children('span#submit-teacherSubject-form-button-text').text('');
    });

    // fetching year dynamically with filters
    $('#searchTeacherSubjects,#matiereFilter').on('change keyup', function () {
        fetchteacherSubjects();
    });

    // default data :
    fetchteacherSubjects();

    // fetching all years :
    function fetchteacherSubjects() {
        var formDatas = $('#filterTeacherSubjectsForm').serialize();
        $.ajax({
            url : "/education/enseignantMatiere",
            type : 'GET',
            data : formDatas,
            success : function(data) {
                $('#teachersSubjectsTable').html(data);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }
});