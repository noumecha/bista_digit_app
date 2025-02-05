$(function(){

    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-section-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var sectionId = $(this).data('section-id');
        var yearId = $(this).data('year-id');
        var sectionIdInput = $('#sectionId');
        var form = $('#classeForm');
        var button = $('#submit-section-form-button');
        var header = $('#modal-section-header');
        var headerText = $('#header-section-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-section-form-button-text').text('Enregistrer');
            headerText.text('Ajouter une nouvelle Section');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-section-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les information de la Section');
            sectionIdInput.val(sectionId);
            $.ajax({
                url: "section/"+sectionId+"/edit",
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

    // When submiting form for updating or creating new personnel
    $(document).on('click','.spinner-submit-section-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-section-form-button-text');
        var sectionId = $('#sectionId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'section/update/' + sectionId : 'section/save';
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
                fetchSections();
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
    $('#create-section-modal').on('hidden.bs.modal', function () {
        const form = $('#classeForm');
        form.trigger('reset');
        $('#modal-section-header').removeClass('bg-primary bg-success');
        $('#submit-section-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-section-form-buuton').children('span#submit-section-form-button-text').text('');
    });

    // fetching enseignements dynamically with filters
    $('#searchSection').on('change keyup', function () {
        fetchSections();
    });

    // default data :
    fetchSections();

    // fetching all notes :
    function fetchSections() {
        var formData = $('#filterSectionForm').serialize();
        $.ajax({
            url : "/education/sections",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#sectionsTable').html(data);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }
});