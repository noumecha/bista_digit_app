$(function(){
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-typeepreuve-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var typeEpreuveId = $(this).data('typeepreuve-id');
        var remplissageIdInput = $('#typeEpreuveId');
        var form = $('#typeEpreuveForm');
        var button = $('#submit-typeepreuve-form-button');
        var header = $('#modal-typeepreuve-header');
        var headerText = $('#header-typeepreuve-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-typeepreuve-form-button-text').text('Enregistrer');
            headerText.text('Creer un nouveau type d\'épreuve');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-typeepreuve-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour le type d\'épreuve');
            remplissageIdInput.val(typeEpreuveId);
            $.ajax({
                url: "epreuves/typeepreuves/"+typeEpreuveId+"/edit",
                type: "GET",
                success: function(res) {
                    // filling form base on the data res
                    fillInputForm(res, form);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        }
    })

    // When submiting form for updating or creating new type epreuve
    $(document).on('click','.spinner-submit-typeepreuve-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-typeepreuve-form-button-text');
        var typeEpreuveId = $('#typeEpreuveId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? 'epreuves/typeepreuves/update/' + typeEpreuveId : 'epreuves/typeepreuves/save';
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
                if(formAction === 'epreuves/typeepreuves/save') {
                    resetForm(form);
                }
                fetchTypeEpreuves();
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

    // reseting form title and color when closing modal :
    $('#create-typeepreuve-modal').on('hidden.bs.modal', function () {
        const form = $('#typeEpreuveForm');
        form.trigger('reset');
        $('#modal-typeepreuve-header').removeClass('bg-primary bg-success');
        $('#submit-typeepreuve-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-typeepreuve-form-button').children('span#submit-typeepreuve-form-button-text').text('');
        $('#evaluation_id').prop("disabled", false);
        $('#date_fin').prop("readOnly", false);
    });

    // fetching typeepreuves dynamically with filters
    $('#searchText').on('change keyup', function () {
        fetchTypeEpreuves();
    });

    // default data :
    fetchTypeEpreuves();

    // fetching all typeepreuves :
    function fetchTypeEpreuves() {
        var formData = $('#filterTypeEpreuveForm').serialize();
        $.ajax({
            url : "/education/epreuves/typeepreuves",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#typeEpreuveTable').html(data);
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
        fetchPage(page, '#typeEpreuveTable');
    });
});