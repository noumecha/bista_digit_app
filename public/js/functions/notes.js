$(function() {
    // hide succes alert by default :$('#msg').hide();

    // onclick for edit
    $(document).on('click', '#edit-note-button', function() {
        var studentId = $(this).data('student-id');

        $('#note-'+ studentId).prop('disabled', false);
    })

    // fetching note dynamically throw filters
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

    // update or create note
    $(document).on('submit', '#note-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var studentId = form.data('student-id');
        var noteValue = $('#note-'+studentId).val();
        var appreciationValue = $('#appreciation-'+studentId).val();

        form.append('<input type="hidden" name="note" value="'+noteValue+'">');
        form.append('<input type="hidden" name="appreciation" value="'+appreciationValue+'">');

        var formData = $(this).serialize();
        $.ajax({
            url: "note/save",
            type: 'POST',
            data: formData,
            success: function(response) {
                //console.log(JSON.stringify(response));
                fetchNotes()
                setTimeout(function() {
                    setSuccessMessage(response.success, '#msg');
                }, 1000);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#errors');
                console.log("errors : ", errors);
            }
        });
    });

    // get globally the classe filter id :
    var classeFilteredId = '';
    $(function() {
        classeFilteredId = $('#classeFilter').val();
    });
    $(document).on('change', '#classeFilter', function() {
        classeFilteredId = $(this).val();
    });

    // delete note :
    $(document).on('click', '#delete-note-button', function(e) {
        e.preventDefault();
        var noteId = $(this).data('note-id');
        var studentId = $(this).data('student-id');
        var form = $('#note-form[data-student-id="' + studentId + '"]');
        form.append('<input type="hidden" name="classe_filter_id" value="'+classeFilteredId+'">');
        var formData = form.serialize()
        console.log("form data ", formData);
        if(!noteId) return;
        $.ajax({
            url: 'notes/' + noteId,
            type: 'DELETE',
            data: formData,
            /*{
                _token: $('input[name="_token"]').val()
            },*/
            success: function(response) {
                fetchNotes()
                setTimeout(function() {
                    setSuccessMessage(response.success, '#msg');
                }, 1000);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#errors');
                console.error('Erreur de suppression de note : ', errors);
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
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#errors');
                //console.error('Erreur de chargement des notes : ', error);
            }
        });
    }

    // success function
    function setSuccessMessage(msg, id) {
        var message = $(id);
        message.stop(true, true);
        message.empty();
        if (Array.isArray(msg)) {
            var list = $('<ul class="list-group text-left"></ul>');
            msg.forEach(function(m) {
                var items = $('<li class="list-group-item list-group-item-danger"></li>').text(m);
                list.append(items);
            });
            message.append(list);
        } else {
            var text = $('<p class="text-center"></p>').text(msg);
            message.append(text);
        }
        message.fadeIn().css('display', 'block');
        setTimeout(function() {
            message.fadeOut();
        }, 3000);
    }

    // default data :
    fetchNotes();
});