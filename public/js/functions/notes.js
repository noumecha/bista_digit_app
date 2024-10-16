$(document).on('onload',function() {
    $('#searchNote','#matiereFilter','#classeFilter','#remplissageFilter').on('change keyup', function () {
        fetchNotes();
    });

    function fetchNotes() {
        var formData = $('#filterNoteForm').serialize();

        $.ajax({
            url : "{{ evaluations.notes }}",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#notesTable').html(data);
            }
        });
    }
});
