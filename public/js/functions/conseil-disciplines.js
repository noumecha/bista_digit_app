$(function(){
    // filtering conseil date and month base on evaluation_id
    $('#evaluation_id').on('change', function() {
        let evaluationId = $(this).val();
        $("#mois").html('<option value="">Selectionnez un mois</option>');
        if (evaluationId) {
            $.get('conseildisciplines/conseildate/' + evaluationId, function(datas) {
                var startDate = new Date(datas.dateDeDebutTrim);
                var endDate = new Date(datas.dateDeFinTrim);
                $('#date_conseil').attr('min', formatDate(startDate));
                $('#date_conseil').attr('max', formatDate(endDate));
                datas.months.forEach(month => {
                    $('#mois').append(`<option value="${month.m}">${month.name}</option>`);
                })
            });
        }
    });
    // filtering student base on classe change :
    $('#classe_id').on('change', function() {
        let classeId = $(this).val();
        $('#user_id').html('<option value="">Sélectionner Un élève</option>');
        if (classeId) {
            $.get('conseildiscipline/students/' + classeId, function(data) {
                data.forEach(student => {
                    $('#user_id').append(`<option value="${student.id}">${student.name}</option>`);
                });
            });
        }
    });
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-conseildiscipline-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var conseilDisciplineId = $(this).data('conseildiscipline-id');
        var studentName = $(this).data('student-name');
        var disciplineIdInput = $('#conseilDisciplineId');
        var form = $('#conseilDisciplineForm');
        var button = $('#submit-conseil-discipline-form-button');
        var header = $('#modal-conseil-discipline-header');
        var headerText = $('#header-conseil-discipline-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-conseil-discipline-form-button-text').text('Enregistrer');
            headerText.text('Creer un nouveau conseil de discipline');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-conseil-discipline-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour le rapport du conseil de discipline de l\'élève : '+ studentName);
            $('#mois').prop("disabled", true);
            $('#user_id').prop("disabled", true);
            $('#classe_id').prop("disabled", true);
            $('#evaluation_id').prop("disabled", true);
            disciplineIdInput.val(conseilDisciplineId);
            $.ajax({
                url: "conseildiscipline/" + conseilDisciplineId + "/edit",
                type: "GET",
                success: function(res) {
                    fillInputForm(res, form);
                    var startDate = new Date(res.dateDeDebutTrim);
                    var endDate = new Date(res.dateDeFinTrim);
                    $('#date_conseil').attr('min', formatDate(startDate));
                    $('#date_conseil').attr('max', formatDate(endDate));
                    $('#mois').html(`<option value="${res.monthId}">${res.monthName}</option>`);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    })
    // When submiting form for updating or creating new discipline
    $(document).on('click','.spinner-submit-conseil-discipline-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-conseil-discipline-form-button-text');
        var conseilDisciplineId = $('#conseilDisciplineId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'conseildiscipline/update/' + conseilDisciplineId : 'conseildiscipline/save';
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
                if(response.success) {
                    setSuccessMessage(response.success, '#modal-form-alert-success');
                    if(formAction === 'conseildiscipline/save') {
                        resetForm(form);
                    }
                    $('#user_id').html('<option value="">Sélectionnez un élève</option>');
                    $('#mois').html('<option value="">Sélectionnez un mois</option>');
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                fetchConseilDisciplines();
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
                    $('#create-conseildiscipline-modal').hide();
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-conseildiscipline-modal').on('hidden.bs.modal', function () {
        const form = $('#conseilDisciplineForm');
        form.trigger('reset');
        $('#modal-conseil-discipline-header').removeClass('bg-primary bg-success');
        $('#submit-conseil-discipline-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-conseil-discipline-form-button').children('span#submit-conseil-discipline-form-button-text').text('');
        $('#user_id').html('<option value="">Sélectionner Un élève</option>');
        $("#mois").html('<option value="">Selectionnez Un mois</option>');
        $('#mois').attr("disabled", false);
        $('#user_id').attr("disabled", false);
        $('#classe_id').attr("disabled", false);
        $('#evaluation_id').prop("disabled", false);
    });

    // fetching disciplines dynamically with filters
    $('#searchText,#monthFilter,#evaluationFilter,#classeFilter').on('change keyup', function () {
        fetchConseilDisciplines();
    });

    // on page load :
    fetchConseilDisciplines();

    // fetching all disciplines :
    function fetchConseilDisciplines() {
        var formData = $('#filterConseilDisciplineForm').serialize();
        $.ajax({
            url : "/education/conseildiscipline",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#conseildisciplinesTable').html(data);
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
        fetchPage(page, '#conseildisciplinesTable');
    });

});