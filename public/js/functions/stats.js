$(function() {
    // fetching bulletins dynamically with filters
    $('#trimestre_id,#classe_id,#type').on('change keyup', function () {
        getStatisticsData();
    });

    // default data :
    getStatisticsData();

    // fetching all bulletins :
    function getStatisticsData() {
        var formData = $('#getStatsForm').serialize();
        $.ajax({
            url : "/statistiques/trimestres",
            type : 'GET',
            data : formData,
            success : function(data) {
                $('#statsTable').html(data);
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
        fetchPage(page, '#statsTable');
    });
});