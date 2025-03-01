$(function() {
    // filtering matiere base on classe change :
    $('#classeFilter').on('change', function() {
        let classeId = $(this).val();
        $('#matiereFilter').html('<option value="">Sélectionner une matière</option>');
        if (classeId) {
            $.get('notes/matieres/' + classeId, function(data) {
                data.forEach(matiere => {
                    $('#matiereFilter').append(`<option value="${matiere.id}">${matiere.libelleMatiere}</option>`);
                });
            });
        }
    });

    // fetching note dynamically throw filters
    $('#searchNote,#matiereFilter,#classeFilter,#remplissageFilter').on('change keyup', function () {
        fetchNotes();
    });

    // create note
    $(document).on('click', '#save-note-button', function() {
        var icon = $(this).children('i#button-icon');
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        icon.addClass('d-none');
        var form = $(this).closest('form')[0];
        var studentId = $(this).data('student-id');
        var noteValue = $('#note-'+studentId).val();
        var appreciationValue = $('#appreciation-'+studentId).val();
        $('#note-input-'+studentId).val(noteValue);
        $('#appreciation-input-'+studentId).val(appreciationValue);
        var formData = new FormData(form);
        $.ajax({
            url: "note/save",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                setSuccessMessage(response.success, '#modal-form-alert-success');
                setTimeout(function() {
                    spinner.addClass('d-none');
                    icon.removeClass('d-none');
                    fetchNotes();
                }, 4000);
            },
            error: function(xhr) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
                setTimeout(function() {
                    spinner.addClass('d-none');
                    icon.removeClass('d-none');
                }, 4000);
            }
        });
    });

    // change appreciation on create
    $(document).on('input', '.note-input', function () {
        var studentId = $(this).data('student-id');
        changeAppreciation(this, '#appreciation-' + studentId);
    });

    // change appreciation on update modal
    $(document).on('input', '.new_value', function () {
        var studentId = $(this).data('student-id');
        changeAppreciation(this, '#update-appreciation-' + studentId);
    });

    // update note
    $(document).on('click', '.spinner-submit-update-note-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var form = $(this).closest('form')[0];
        var noteId = $(form).find('[name="noteId"]').val();
        var modalId = $(this).closest('div.modal').prop('id');
        var studentId = $(this).closest('div.modal').data('student-id');
        var formData = new FormData(form);
        formData.append('_method', 'PUT');
        $.ajax({
            url: "note/update/"+noteId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.error)
                    setSuccessMessage(response.error, '#note-modal-form-alert-errors-'+noteId);
                if(response.success)
                    setSuccessMessage(response.success, '#note-modal-form-alert-success-'+noteId);
                setTimeout(function() {
                    spinner.addClass('d-none');
                    fetchNotes();
                }, 4000);
            },
            error: function(xhr) {
                var errors = []
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    stylingErrors(xhr.responseJSON.errors, studentId);
                    var datas = Object.entries(xhr.responseJSON.errors);
                    errors = datas.map(error => error[1][0]);
                    $('#'+modalId).on('hidden.bs.modal', function() {
                        return false;
                    });
                } else {
                    setSuccessMessage('Erreur inconue' , '#note-modal-form-alert-errors-'+noteId);
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#note-modal-form-alert-errors-'+noteId);
            }
        });
    });

    // get globally the classe filter id :
    var classeFilteredId = '';
    $(document).on('change', '#classeFilter', function() {
        classeFilteredId = $(this).val();
    });

    // fetching all notes :
    function fetchNotes() {
        var formData = $('#filterNoteForm').serialize();
        $.ajax({
            url : "notes",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#notesTable').html(data);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }

    // default function running :
    fetchNotes();
});