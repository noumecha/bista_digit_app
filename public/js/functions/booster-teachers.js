$(function(){
    // filtering student base on the classe id
    $('#user_id').on('change', function() {
        let userId = $(this).val();
        $('#booster_matiere_id').html('<option value="">Attribuer une matière</option>');
        if (userId) {
            $.get('/booster/teachers/matieres/' + userId, function(datas) {
                datas.forEach(matiere => {
                    $('#booster_matiere_id').append(`<option value="${matiere.id}">${matiere.name}</option>`);
                });
            });
        }
    });
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-boosterteacher-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var boosterTeacherId = $(this).data('boosterteacher-id');
        var boosterTeacherIdInput = $('#boosterTeacherId');
        var form = $('#boosterTeacherForm');
        var button = $('#submit-boosterteacher-form-button');
        var header = $('#modal-boosterteacher-header');
        var headerText = $('#header-boosterteacher-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-boosterteacher-form-button-text').text('Enregistrer');
            headerText.text('Ajouter un enseignant au programme');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-boosterteacher-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la configuration');
            boosterTeacherIdInput.val(boosterTeacherId);
            $.ajax({
                url: "teacher/"+boosterTeacherId+"/edit",
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
    // When submiting form for updating or creating new boosterteacher
    $(document).on('click','.spinner-submit-boosterteacher-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-boosterteacher-form-button-text');
        var boosterTeacherId = $('#boosterTeacherId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'teachers/update' + boosterTeacherId : 'teachers/save';
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
                if(formAction === 'teachers/save') {
                    resetForm(form);
                    setTimeout(function() {
                        window.editor.setData('');
                    }, 4000);
                }
                fetchBoosterTeachers();
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
                    $('#create-boosterteacher-modal').hide();
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-boosterteacher-modal').on('hidden.bs.modal', function () {
        const form = $('#boosterTeacherForm');
        form.trigger('reset');
        $('#modal-boosterteacher-header').removeClass('bg-primary bg-success');
        $('#submit-boosterteacher-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-boosterteacher-form-button').children('span#submit-boosterteacher-form-button-text').text('');
    });

    // fetching boosterteachers dynamically with filters
    $('#searchText,#classeFilter,#matiereFilter').on('change keyup', function () {
        fetchBoosterTeachers();
    });

    // default data :
    fetchBoosterTeachers();

    // fetching all boosterteachers :
    function fetchBoosterTeachers() {
        var formData = $('#filterBoosterTeacherForm').serialize();
        $.ajax({
            url : "teachers",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#boosterTeachersTable').html(data);
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
        fetchPage(page, '#boosterTeachersTable');
    });

});