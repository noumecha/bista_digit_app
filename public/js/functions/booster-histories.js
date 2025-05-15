$(function(){
    // fetching with filters
    $('#searchStudent, #classeFilter, #evaluationFilter, #matiereFilter')
    .on('change keyup', function () {
        fetchNotesHitory();
    });

    // default data :
    fetchNotesHitory();

    // fetching all remplissages :
    function fetchNotesHitory() {
        var formData = $('#filterBoosterHistoryForm').serialize();
        $.ajax({
            url : "/programmes/booster/notes/modifications",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#boosterHistoryTable').html(data);
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
        fetchPage(page, '#boosterHistoryTable');
    });
});