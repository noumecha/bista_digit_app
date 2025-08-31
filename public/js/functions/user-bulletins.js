$(function(){
    // fetching bulletins dynamically with filters
    $('#trimestreFilter,#evaluationFilter,#typeFilter').on('change keyup', function () {
        fetchUserBulletins();
    });

    // default data :
    fetchUserBulletins();

    // fetching all bulletins :
    function fetchUserBulletins() {
        var formData = $('#filterBulletinForm').serialize();
        $.ajax({
            url : "/education/bulletins/student",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#userBulletinsTable').html(data);
                initializeCountdowns();
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
        fetchPage(page, '#userBulletinsTable');
    });
});