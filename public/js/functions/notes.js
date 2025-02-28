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

    // change appreciation
    // on create
    $(document).on('input', '.note-input', function () {
        var studentId = $(this).data('student-id');
        var noteVal = $(this).val();
        var appreciationVal = $('#appreciation-'+studentId);

        var validPattern = /^\d*(\.\d{0,2})?$/;
        if (!validPattern.test(noteVal) || noteVal > 20 || noteVal < 0) {
            $(this).val(noteVal.slice(0, -1));
            return ;
        }
        noteVal = parseFloat(noteVal);
        var appreciationText = '';
        if (parseFloat(noteVal) < 10) {
            appreciationText = "D (CNA)";
        } else if (parseFloat(noteVal) >= 10 && parseFloat(noteVal) < 12) {
            appreciationText = "CMA (C)";
        } else if (parseFloat(noteVal) >= 12 && parseFloat(noteVal) < 14) {
            appreciationText = "CA (C+)";
        } else if (parseFloat(noteVal) >= 14 && parseFloat(noteVal) < 15) {
            appreciationText = "CBA (B)";
        } else if (parseFloat(noteVal) >= 15 && parseFloat(noteVal) < 16) {
            appreciationText = "CBA (B+)";
        } else if (parseFloat(noteVal) >= 16 && parseFloat(noteVal) < 18) {
            appreciationText = "CTBA (A)";
        } else if (parseFloat(noteVal) >= 18 && parseFloat(noteVal) <= 20) {
            appreciationText = "CTBA (A+)";
        } else {
            appreciationText = "NOTE INVALIDE";
        }
        appreciationVal.val(appreciationText);
    });
    // on update modal
    changeAppreciation('#new_value', '#update-appreciation-');

    // update note
    $(document).on('click', '.spinner-submit-update-note-form-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var form = $(this).closest('form')[0];
        var noteId = $('#noteId').val();
        var formData = new FormData(form);
        $.ajax({
            url: "note/update/"+noteId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                setSuccessMessage(response.success, '#modal-form-alert-success');
                setTimeout(function() {
                    spinner.addClass('d-none');
                    fetchNotes();
                }, 4000);
            },
            error: function(xhr) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
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