$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-teacher-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var teacherId = $(this).data('teacher-id');
        var yearId = $(this).data('year-id');
        var teacherIdInput = $('#teacherId');
        var teacherName = $(this).data('teacher-name');
        var form = $('#teacherForm');
        var button = $('#submit-teacher-form-button');
        var header = $('#modal-teacher-header');
        var headerText = $('#header-teacher-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-teacher-form-button-text').text('Enregistrer');
            headerText.text('Ajouter un nouvel enseignant');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-teacher-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les configurations de l\'enseignant : ' + teacherName);
            teacherIdInput.val(teacherId);
            $.ajax({
                url: "teacher/"+ teacherId + "/edit/" + yearId,
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

    // When submiting form for updating or creating new teacher spinner-submit-year-form-button
    $(document).on('click','.spinner-submit-teacher-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-teacher-form-button-text');
        var teacherId = $('#teacherId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'teacher/update/' + teacherId : 'teacher/save';
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
                if(formAction === 'teacher/save') {
                    resetForm(form);
                }
                fetchTeachers();
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
    $('#create-teacher-modal').on('hidden.bs.modal', function () {
        const form = $('#teacherForm');
        form.trigger('reset');
        $('#modal-teacher-header').removeClass('bg-primary bg-success');
        $('#submit-teacher-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-teacher-form-button').children('span#submit-teacher-form-button-text').text('');
    });

    // fetching year dynamically with filters
    $('#searchTeacher').on('change keyup', function () {
        fetchTeachers();
    });

    // default data :
    fetchTeachers();

    // fetching all years :
    function fetchTeachers() {
        var formDatas = $('#filterTeacherForm').serialize();
        $.ajax({
            url : "/utilisateur/teachers",
            type : 'GET',
            data : formDatas,
            success : function(data) {
                $('#teachersTable').html(data);
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
        fetchPage(page, '#teachersTable');
    });
});