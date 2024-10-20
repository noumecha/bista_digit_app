$(function() {
    $('#searchNote,#matiereFilter,#classeFilter,#remplissageFilter').on('change keyup', function () {
        fetchNotes();
    });

    // change appreciation
    $(document).on('input', '.note-input', function () {
        var studentId = $(this).data('student-id');
        var noteVal = $(this).val();
        var appreciationVal = $('#appreciation-'+studentId);

        // prevent text input on note input
        //var validPattern = /^(0|[1-9]\d*)(\.\d{0,2})?$/;
        var validPattern = /^\d*(\.\d{0,2})?$/;

        if (!validPattern.test(noteVal) || noteVal > 20 || noteVal < 0) {
            $(this).val(noteVal.slice(0, -1));
            return ;
        }

        noteVal = parseFloat(noteVal);
        var appreciationText = '';

        if (parseFloat(noteVal) >= 0 && parseFloat(noteVal) <= 11) {
            appreciationText = "NON ACQUIS(NA)";
        } else if (parseFloat(noteVal) >= 12 && parseFloat(noteVal) <= 16) {
            appreciationText = "EN COURS D'ACQUISITION(ECA)";
        } else if (parseFloat(noteVal) >= 17 && parseFloat(noteVal) <= 19) {
            appreciationText = "ACQUIS(A)";
        } else if (parseFloat(noteVal) > 19 && parseFloat(noteVal) <= 20) {
            appreciationText = "Expert (A+)";
        } else {
            appreciationText = "NOTE INVALIDE";
        }

        appreciationVal.val(appreciationText);
    });

    // update or submit note
    $(document).on('submit', '.note-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var studentId = form.data('student-id');
        var noteValue = $('#note-'+studentId).val();
        var appreciationValue = $('#appreciation-'+studentId).val();

        form.append('<input type="hidden" name="note" value="'+noteValue+'">');
        form.append('<input type="hidden" name="appreciation" value="'+appreciationValue+'">');

        var formData = $(this).serialize();
        console.log(formData);
        $.ajax({
            url: 'save/note',
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log(response.success);
                fetchNotes();
            },
            error: function(xhr, status, error) {
                console.error('Erreur pendant la sauvegarde à jour : ', error);
            }
        });
    });

    // delete note :
    $(document).on('click', '.delete-note', function() {
        var noteId = $(this).data('note-id');
        console.log("delete works:");
        if(!noteId) return;
        $.ajax({
            url: 'notes/' + noteId,
            type: 'DELETE',
            data: formData,
            success: function(response) {
                fetchNotes();
            },
            error: function(xhr, status, error) {
                console.error('Erreur de suppression de note : ', error);
            }
        });
    });

    // fetching all notes :
    function fetchNotes() {
        var formData = $('#filterNoteForm').serialize();
        //console.log("works !");
        $.ajax({
            url : "notes",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#notesTable').html(data);
            },
            error: function(xhr, status, error) {
                console.error('Erreur de chargement des notes : ', error);
            }
        });
    }

    // default data :
    fetchNotes();
});