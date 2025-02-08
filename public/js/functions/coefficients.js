$(function(){
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-coefficient-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var coefficientId = $(this).data('coefficient-id');
        var yearId = $(this).data('year-id');
        var coefficientIdInput = $('#coefficientId');
        var form = $('#coefficientForm');
        var button = $('#submit-coefficient-form-button');
        var header = $('#modal-coefficient-header');
        var headerText = $('#header-coefficient-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-coefficient-form-button-text').text('Enregistrer');
            headerText.text('Configurer une matière');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-coefficient-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la configuration de la matière');
            coefficientIdInput.val(coefficientId);
            $.ajax({
                url: "coefficient/"+coefficientId+"/edit/" + yearId,
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

    // When submiting form for updating or creating coefficient
    $(document).on('click','.spinner-submit-coefficient-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-coefficient-form-button-text');
        var coefficientId = $('#coefficientId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'coefficient/update/' + coefficientId : 'coefficient/save';
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
                fetchCoefficients();
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
    $('#create-coefficient-modal').on('hidden.bs.modal', function () {
        const form = $('#coefficientForm');
        form.trigger('reset');
        $('#modal-coefficient-header').removeClass('bg-primary bg-success');
        $('#submit-coefficient-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-coefficient-form-buuton').children('span#submit-coefficient-form-button-text').text('');
    });

    // fetching coefficients dynamically with filters
    $('#searchCoef,#classeFilter,#matiereFilter,#groupFilter').on('change keyup', function () {
        fetchCoefficients();
    });

    // default data :
    fetchCoefficients();

    // fetching all coefficients :
    function fetchCoefficients() {
        var formData = $('#filterCoefficientForm').serialize();
        $.ajax({
            url : "/education/coefficients",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#coefficientsTable').html(data);
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
        fetchPage(page, '#coefficientsTable');
    });
});