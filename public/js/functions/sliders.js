$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-slider-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var sliderId = $(this).data('slider-id');
        var sliderIdInput = $('#sliderId');
        var form = $('#sliderForm');
        var button = $('#submit-slider-form-button');
        var header = $('#modal-slider-header');
        var headerText = $('#header-slider-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-slider-form-button-text').text('Enregistrer');
            headerText.text('Creer un nouveau slider');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-slider-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les informations du slider');
            sliderIdInput.val(sliderId);
            $.ajax({
                url: sliderId+"/edit",
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
    // When submiting form for updating or creating new slider
    $(document).on('click','.spinner-submit-slider-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-slider-form-button-text');
        var sliderId = $('#sliderId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'update/' + sliderId : 'save';
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
                if(formAction === 'save') {
                    resetForm(form);
                    setTimeout(function() {
                        window.editor.setData('');
                    }, 4000);
                }
                fetchSliders();
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
                    $('#create-slider-modal').hide();
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-slider-modal').on('hidden.bs.modal', function () {
        const form = $('#sliderForm');
        form.trigger('reset');
        $('#modal-slider-header').removeClass('bg-primary bg-success');
        $('#submit-slider-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-slider-form-button').children('span#submit-slider-form-button-text').text('');
    });

    // fetching sliders dynamically with filters
    $('#searchText').on('change keyup', function () {
        fetchSliders();
    });

    // default data :
    fetchSliders();

    // fetching all sliders :
    function fetchSliders() {
        var formData = $('#filterSliderForm').serialize();
        $.ajax({
            url : "list",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#slidersTable').html(data);
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
        fetchPage(page, '#slidersTable');
    });

});