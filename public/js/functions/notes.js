$(function() {
    $('#searchNote,#matiereFilter,#classeFilter,#remplissageFilter').on('change keyup', function () {
        fetchNotes();
    });

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
                console.error('Error fetching notes : ', error);
            }
        });
    }
});
