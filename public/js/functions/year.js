$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-year-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var yearId = $(this).data('year-id');
        var schoolYearIdInput = $('#schoolYearId');
        var yearLibelle = $(this).data('year-libelle');
        var form = $('#schoolYearForm');
        var button = $('#submit-year-form-button');
        var header = $('#modal-year-header');
        var headerText = $('#header-year-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-year-form-button-text').text('Enregistrer');
            headerText.text('Ajouter une nouvelle année scolaire');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-year-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les configurations de l\'année : ' + yearLibelle);
            schoolYearIdInput.val(yearId);
            $.ajax({
                url: "/annee_scolaire/edit/"+yearId,
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

    // When submiting form for updating or creating new year
    $(document).on('click','.spinner-submit-year-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-year-form-button-text');
        var schoolYearId = $('#schoolYearId').val();
        var formDatas = $(this).closest('form').serialize();
        var formMethod = buttonText.text() === 'Mettre à jour' ? 'PUT' : 'POST';
        var formAction = buttonText.text() === 'Mettre à jour' ? 'update/' + schoolYearId : 'save';
        var modalId = $(this).closest('div.modal').prop('id');
        try {
            $.ajax({
                url: formAction,
                type: formMethod,
                data: formDatas,
                success: function(response) {
                    if(response.error)
                        setSuccessMessage(response.error, '#modal-form-alert-errors');
                    if(response.success)
                        setSuccessMessage(response.success, '#modal-form-alert-success');
                    setTimeout(function() {
                        spinner.addClass('d-none');
                    }, 4000);
                    fetchYears();
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
        } catch (error) {
            console.log(error);
        }
    });
    // reseting form title and color :
    $('#create-year-modal').on('hidden.bs.modal', function () {
        const form = $('#schoolYearForm');
        form.trigger('reset');
        $('#modal-year-header').removeClass('bg-primary bg-success');
        $('#submit-year-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-year-form-button').children('span#submit-year-form-button-text').text('');
    });

    // fetching year dynamically with filters
    $('#searchYear').on('change keyup', function () {
        fetchYears();
    });

    // default data :
    fetchYears();

    // fetching all years :
    function fetchYears() {
        var formDatas = $('#filterYearForm').serialize();
        $.ajax({
            url : "/annee_scolaire/list",
            type : 'GET',
            data : formDatas,
            success : function(data) {
                $('#yearsTable').html(data);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }
});