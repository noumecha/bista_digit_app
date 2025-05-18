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
    $(document).on('click', '[data-bs-target="#create-club-modal"]', function(e) {
        e.preventDefault();
        // setting up variables
        var action = $(this).data('action');
        var clubId = $(this).data('club-id');
        var clubIdInput = $('#clubId');
        var form = $('#clubForm');
        var button = $('#submit-club-form-button');
        var header = $('#modal-club-header');
        var headerText = $('#header-club-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-club-form-button-text').text('Enregistrer');
            headerText.text('Creer un nouveau club');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-club-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour les informations du club');
            clubIdInput.val(clubId);
            $.ajax({
                url: clubId+"/edit",
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
    // When submiting form for updating or creating new club
    $(document).on('click','.spinner-submit-club-form-button', function() {
        // ckeditor synchronize before save
        if (window.editor) {
            $('textarea#content').val(window.editor.getData());
        }
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-club-form-button-text');
        var clubId = $('#clubId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'update/' + clubId : 'save';
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
                fetchClubs();
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
                    $('#create-club-modal').hide();
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-club-modal').on('hidden.bs.modal', function () {
        const form = $('#clubForm');
        form.trigger('reset');
        $('#modal-club-header').removeClass('bg-primary bg-success');
        $('#submit-club-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-club-form-button').children('span#submit-club-form-button-text').text('');
        window.editor.setData('');
        $('.slider-group').remove();
    });

    // fetching clubs dynamically with filters
    $('#searchText').on('change keyup', function () {
        fetchClubs();
    });

    // default data :
    fetchClubs();

    // fetching all clubs :
    function fetchClubs() {
        var formData = $('#filterClubForm').serialize();
        $.ajax({
            url : "list",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#clubsTable').html(data);
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
        fetchPage(page, '#clubsTable');
    });

});