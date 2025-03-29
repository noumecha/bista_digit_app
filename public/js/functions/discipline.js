$(function(){
    // load function to enable or disable decision field :
    enableDecision("#heures_absence", "#heures_justifiees", "#decision_container");
    // filtering student base on classe change :
    $('#classe_id').on('change', function() {
        let classeId = $(this).val();
        $('#user_id').html('<option value="">Sélectionner Un élève</option>');
        if (classeId) {
            $.get('discipline/students/' + classeId, function(data) {
                data.forEach(student => {
                    $('#user_id').append(`<option value="${student.id}">${student.name}</option>`);
                });
            });
        }
    });
    // showing or unshow decision :
    $("#heures_absence, #heures_justifiees").on("input", function() {
        enableDecision("#heures_absence", "#heures_justifiees", "#decision_container");
    });
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-discipline-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var disciplineId = $(this).data('discipline-id');
        var studentName = $(this).data('student-name');
        var disciplineIdInput = $('#disciplineId');
        var form = $('#disciplineForm');
        var button = $('#submit-discipline-form-button');
        var header = $('#modal-discipline-header');
        var headerText = $('#header-discipline-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-discipline-form-button-text').text('Enregistrer');
            headerText.text('Creer une nouvelle catégorie d\'actualité');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-discipline-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour l\'état disciplinaire de l\'élève : '+ studentName);
            $('#classe_id').prop("disabled", true);
            $('#mois').prop("disabled", true);
            $('#user_id').prop("disabled", true);
            $('#evaluation_id').prop("disabled", true);
            disciplineIdInput.val(disciplineId);
            $.ajax({
                url: "discipline/" + disciplineId + "/edit",
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
    // When submiting form for updating or creating new discipline
    $(document).on('click','.spinner-submit-discipline-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-discipline-form-button-text');
        var disciplineId = $('#disciplineId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'discipline/update/' + disciplineId : 'discipline/save';
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
                    if(formAction === 'discipline/save') {
                        resetForm(form);
                    }
                    $('#user_id').html('<option value="">Sélectionnez un élève</option>');
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                fetchDisciplines();
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
                    $('#create-discipline-modal').hide();
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-discipline-modal').on('hidden.bs.modal', function () {
        const form = $('#disciplineForm');
        form.trigger('reset');
        $('#modal-discipline-header').removeClass('bg-primary bg-success');
        $('#submit-discipline-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-discipline-form-buuton').children('span#submit-discipline-form-button-text').text('');
        $('#user_id').html('<option value="">Sélectionner Un élève</option>');
        $('#classe_id').attr("disabled", false);
        $('#mois').attr("disabled", false);
        $('#user_id').attr("disabled", false);
        $('#evaluation_id').prop("disabled", false);
    });

    // fetching disciplines dynamically with filters
    $('#searchDiscipline,#classeFilter,#monthFilter,#evaluationFilter').on('change keyup', function () {
        fetchDisciplines();
    });

    // on page load :
    fetchDisciplines();

    // fetching all disciplines :
    function fetchDisciplines() {
        var formData = $('#filterDisciplineForm').serialize();
        $.ajax({
            url : "/education/discipline",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#disciplinesTable').html(data);
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
        fetchPage(page, '#disciplinesTable');
    });

});