$(function(){
    // on change durree - update date fin
    $('#duree, #date_debut').on('input', function() {
        updateEndDate($('#date_debut'), $('#duree'), true, $('#date_fin'));
    })
    // when the modal is opened
    $(document).on('click', '[data-bs-target="#create-devoir-modal"]', function(e) {
        e.preventDefault();
        // filtering devoir dates base on the current year
        $.get('devoirs/yeardates', function(data) {
            var startDate = new Date(data.dateDeDebutYear);
            var endDate = new Date(data.dateDeFinYear);
            $('#date_debut').attr('min', formatDate(startDate));
            $('#date_debut').attr('max', formatDate(endDate));
            $('#date_fin').attr('min', formatDate(startDate));
            $('#date_fin').attr('max', formatDate(endDate));
        });
        // setting up variables
        var action = $(this).data('action');
        var devoirId = $(this).data('devoir-id');
        var yearId = $(this).data('year-id');
        var devoirIdInput = $('#devoirId');
        var form = $('#devoirForm');
        var button = $('#submit-devoir-form-button');
        var header = $('#modal-devoir-header');
        var headerText = $('#header-devoir-text');

        // reseting
        header.removeClass('bg-primary bg-success');
        button.removeClass('btn-outline-primary btn-outline-success');
        form.trigger('reset');

        // kind of action
        if (action == "create") {
            header.addClass('bg-primary');
            button.addClass('btn-outline-primary');
            button.children('span#submit-devoir-form-button-text').text('Enregistrer');
            headerText.text('Creer un nouveau devoir (QCM)');
        } else if (action == "edit") {
            header.addClass('bg-success');
            button.addClass('btn-outline-success');
            button.children('span#submit-devoir-form-button-text').text('Mettre à jour');
            headerText.text('Mettre à jour la configuration du devoir');
            devoirIdInput.val(devoirId);
            $.ajax({
                url: "devoirs/"+devoirId+"/edit/"+yearId,
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
    // When submiting form for updating or creating new devoir
    $(document).on('click','.spinner-submit-devoir-form-button', function() {
        // ckeditor synchronize before save
        if (window.editor) {
            $('textarea#content').val(window.editor.getData());
        }
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var buttonText = $(this).children('span#submit-devoir-form-button-text');
        var devoirId = $('#devoirId').val();
        var form = $(this).closest('form')[0];
        var formData = new FormData(form);
        var formAction = buttonText.text() === 'Mettre à jour' ? 'devoirs/update/' + devoirId : 'devoirs/save';
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
                if(formAction === 'devoirs/save') {
                    resetForm(form);
                    setTimeout(function() {
                        window.editor.setData('');
                    }, 4000);
                }
                fetchDevoirs();
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
                    $('#create-devoir-modal').hide();
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    });
    // reseting form title and color :
    $('#create-devoir-modal').on('hidden.bs.modal', function () {
        const form = $('#devoirForm');
        form.trigger('reset');
        $('#modal-devoir-header').removeClass('bg-primary bg-success');
        $('#submit-devoir-form-button').removeClass('btn-outline-primary btn-outline-success');
        $('#submit-devoir-form-buuton').children('span#submit-devoir-form-button-text').text('');
        window.editor.setData('');
    });

    // fetching devoirs dynamically with filters
    $('#searchDevoir,#classeFilter,#matiereFilter,#statutFilter').on('change keyup', function () {
        fetchDevoirs();
    });

    // default data :
    fetchDevoirs();

    // fetching all devoirs :
    function fetchDevoirs() {
        var formData = $('#filterDevoirForm').serialize();
        $.ajax({
            url : "/education/devoirs",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#devoirsTable').html(data);
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
        fetchPage(page, '#devoirsTable');
    });

});