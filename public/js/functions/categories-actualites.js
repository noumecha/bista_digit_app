$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-categorieActu-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var categorieActuId = $(this).data('categorieactu-id');
        var categorieActuIdInput = $('#categorieActuId');
        var form = $('#categorieActuForm');
        var button = $('#submit-categorieActu-form-button');
        var header = $('#modal-categorieActu-header');
        var headerText = $('#header-categorieActu-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-categorieActu-form-button-text').text('Enregistrer');
            headerText.text('Creer une nouvelle catégorie d\'actualité');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-categorieActu-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la catégorie d\'actualité');
            categorieActuIdInput.val(categorieActuId);
            $.ajax({
                url: "actualites/" + categorieActuId + "/edit",
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
    // When submiting form for updating or creating new categorieActu
    $(document).on('click','.spinner-submit-categorieActu-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-categorieActu-form-button-text');
        var categorieActuId = $('#categorieActuId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'actualites/update/' + categorieActuId : 'actualites/save';
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
                if(formAction === 'actualites/save') {
                    resetForm(form);
                }
                fetchCategoriesActus();
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
                    $('#create-categorieActu-modal').hide();
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-categorieActu-modal').on('hidden.bs.modal', function () {
        const form = $('#categorieActuForm');
        form.trigger('reset');
        $('#modal-categorieActu-header').removeClass('bg-primary bg-success');
        $('#submit-categorieActu-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-categorieActu-form-buuton').children('span#submit-categorieActu-form-button-text').text('');
    });

    // fetching categories actus dynamically with filters
    $('#searchCategorie').on('change keyup', function () {
        fetchCategoriesActus();
    });

    // default data :
    fetchCategoriesActus();

    // fetching all actualites :
    function fetchCategoriesActus() {
        var formData = $('#filterCategorieActuForm').serialize();
        $.ajax({
            url : "/categories/actualites",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#categorieActusTable').html(data);
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
        fetchPage(page, '#categorieActusTable');
    });

});