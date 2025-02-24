$(function(){
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-trimestre-modal"]', function(e) {
        // filtering trimestre dates base on the selected current year
        let yearId = $('#annee_scolaire_id').val();
        if (yearId) {
            $.get('trimestres/years/' + yearId, function(data) {
                var startDate = new Date(data.dateDeDebutYear);
                var endDate = new Date(data.dateDeFinYear);
                $('#dateDeDebut').attr('min', formatDate(startDate));
                $('#dateDeDebut').attr('max', formatDate(endDate));
                $('#dateDeFin').attr('min', formatDate(startDate));
                $('#dateDeFin').attr('max', formatDate(endDate));
            });
        }
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var trimestreId = $(this).data('trimestre-id');
        var trimestreIdInput = $('#trimestreId');
        var form = $('#trimestreForm');
        var button = $('#submit-trimestre-form-button');
        var header = $('#modal-trimestre-header');
        var headerText = $('#header-trimestre-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-trimestre-form-button-text').text('Enregistrer');
            headerText.text('Creer un nouveau trimestre');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-trimestre-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les informations du trimestre');
            trimestreIdInput.val(trimestreId);
            $.ajax({
                url: "trimestres/"+trimestreId+"/edit",
                type: "GET",
                success: function(res) {
                    // filling form base on the res data
                    fillInputForm(res, form);
                    // Set date picker range based on trimester dates
                    var startDate = new Date(res.dateDeDebutYear);
                    var endDate = new Date(res.dateDeFinYear);
                    $('#dateDeDebut').attr('min', formatDate(startDate));
                    $('#dateDeDebut').attr('max', formatDate(endDate));
                    $('#dateDeFin').attr('min', formatDate(startDate));
                    $('#dateDeFin').attr('max', formatDate(endDate));
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    })

    // When submiting form for updating or creating new trimestre
    $(document).on('click','.spinner-submit-trimestre-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-trimestre-form-button-text');
        var trimestreId = $('#trimestreId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? 'trimestres/update/' + trimestreId : 'trimestres/save';
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
                // reset form after creation
                if(formAction === 'trimestres/save') {
                    resetForm(form);
                }
                fetchTrimestres();
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
    $('#create-trimestre-modal').on('hidden.bs.modal', function () {
        const form = $('#trimestreForm');
        form.trigger('reset');
        $('#modal-trimestre-header').removeClass('bg-primary bg-success');
        $('#submit-trimestre-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-trimestre-form-buuton').children('span#submit-trimestre-form-button-text').text('');
    });

    // fetching trimestres dynamically with filters
    $('#searchTrimestre').on('change keyup', function () {
        fetchTrimestres();
    });

    // default data :
    fetchTrimestres();

    // fetching all trimestres :
    function fetchTrimestres() {
        var formData = $('#filterTrimestreForm').serialize();
        $.ajax({
            url : "/evaluation/trimestres",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#trimestresTable').html(data);
                initializeCountdowns();
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
        fetchPage(page, '#trimestresTable');
    });

});