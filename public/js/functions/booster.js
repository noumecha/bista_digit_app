$(function(){
    // manage the slider dynamically
    let sliderId = 0;
    $(document).on('click', '#add-slider', function () {
        sliderId++;
        const html = `
            <div class="slider-group" id="slider-${sliderId}">
                <input type="file" id="sliders[${sliderId}][image]"
                    name="sliders[${sliderId}][image]"
                    placeholder="Taille.<= 4Mo" class="form-control mb-1" />
                <input type="text" id="sliders[${sliderId}][title]"
                    name="sliders[${sliderId}][title]"
                    placeholder="description de l'image" class="form-control mb-1" />
                <button type="button" class="btn btn-danger remove-slider">Supprimer</button>
            </div>
        `;
        $('#slider-wrapper').append(html);
    });
    $(document).on('click', '.remove-slider', function () {
        $(this).closest('.slider-group').remove();
    });
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#update-pageconfiguration-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var pageconfigurationId = $(this).data('pageconfiguration-id');
        var pageconfigurationIdInput = $('#pageconfigurationId');
        var action = pageconfigurationId !== "" ? "edit" : "create";
        var form = $('#pageconfigurationForm');
        var button = $('#submit-pageconfiguration-form-button');
        var header = $('#modal-pageconfiguration-header');
        var headerText = $('#header-pageconfiguration-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-pageconfiguration-form-button-text').text('Enregistrer');
            headerText.text('Modifier la page du programme booster');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-pageconfiguration-form-button-text').text('Mettre à jour');
            headerText.text('Modifier la page du programme booster');
            pageconfigurationIdInput.val(pageconfigurationId);
            $.ajax({
                url: "booster/"+pageconfigurationId+"/edit",
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

    // When submiting form for updating or creating new remplissage
    $(document).on('click','.spinner-submit-pageconfiguration-form-button', function() {
        // ckeditor synchronize before save
        if (window.editor) {
            $('textarea#content').val(window.editor.getData());
        }
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-pageconfiguration-form-button-text');
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);

        // Kind of action
        var formAction = buttonText.text() === 'Mettre à jour' ? 'booster/update' : 'booster/create';
        var modalId = $(this).closest('div.modal').prop('id');
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
                console.log(formAction);
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                /*if (formAction === 'page_configuration/create') {
                    // close the form :
                    $('#'+modalId).hide();
                    $('.modal-backdrop').remove();
                }*/
                setTimeout(function() {
                    fetchConfig();
                }, 4000);
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
    $('#update-pageconfiguration-modal').on('hidden.bs.modal', function () {
        const form = $('#pageconfigurationForm');
        form.trigger('reset');
        $('#modal-pageconfiguration-header').removeClass('bg-primary bg-success');
        $('#submit-pageconfiguration-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-pageconfiguration-form-button').children('span#submit-pageconfiguration-form-button-text').text('');
        window.editor.setData('');
        $('.slider-group').remove();
    });
    // fetching all remplissages :
    function fetchConfig() {
        $.ajax({
            url : "booster/",
            type : 'GET',
            success : function(response) {
                console.log("Page configuration loaded!");
                window.location.href = "booster";
            },
            error: function(xhr, status, error) {
                if (xhr && xhr.responseJSON.errors) {
                    var datas = Object.entries(xhr.responseJSON.errors);
                    var errors = datas.map(error => error[1][0]);
                    setSuccessMessage(errors, '#modal-form-alert-errors');
                }
            }
        });
    }
});