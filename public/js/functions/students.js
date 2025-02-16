$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-student-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var studentId = $(this).data('student-id');
        var yearId = $(this).data('year-id');
        var studentIdInput = $('#studentId');
        var studentName = $(this).data('student-name');
        var form = $('#studentForm');
        var button = $('#submit-student-form-button');
        var header = $('#modal-student-header');
        var headerText = $('#header-student-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-student-form-button-text').text('Enregistrer');
            headerText.text('Ajouter une nouvel élève');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-student-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les configurations de l\'élève : ' + studentName);
            studentIdInput.val(studentId);
            $.ajax({
                url: "student/"+ studentId + "/edit/" + yearId,
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

    // When submiting form for updating or creating new student spinner-submit-year-form-button
    $(document).on('click','.spinner-submit-student-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-student-form-button-text');
        var studentId = $('#studentId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'student/update/' + studentId : 'student/save';
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
                if(formAction === 'student/save') {
                    resetForm(form);
                }
                fetchStudents();
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
    $('#create-student-modal').on('hidden.bs.modal', function () {
        const form = $('#studentForm');
        form.trigger('reset');
        $('#modal-student-header').removeClass('bg-primary bg-success');
        $('#submit-student-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-student-form-button').children('span#submit-student-form-button-text').text('');
    });

    // fetching year dynamically with filters
    $('#searchStudent,#classFilter').on('change keyup', function () {
        fetchStudents();
    });

    // default data :
    fetchStudents();

    // fetching all years :
    function fetchStudents() {
        var formDatas = $('#filterStudentForm').serialize();
        $.ajax({
            url : "/utilisateur/students",
            type : 'GET',
            data : formDatas,
            success : function(data) {
                $('#studentsTable').html(data);
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
        fetchPage(page, '#studentsTable');
    });
});