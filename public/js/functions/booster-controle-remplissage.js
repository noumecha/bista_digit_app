$(function(){
    // fetching with filters
    $('#searchTeacher, #classeFilter, #evaluationFilter, #matiereFilter').on('change keyup', function () {
        fetchControles();
    });

    // default data :
    fetchControles();

    // fetching all remplissages :
    function fetchControles() {
        var formData = $('#filterControlForm').serialize();
        $.ajax({
            url : "/programme/booster/notes/controles",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#boosterControleRemplissagesTable').html(data);
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
        fetchPage(page, '#boosterControleRemplissagesTable');
    });
});