$(function() {
    // stats
    function stats(button) {
        var spinner = $(button).children('span.spinner-border');
        spinner.removeClass('d-none');
        var form = $(button).closest('form')[0];
        var formData = new FormData(form);
        $.ajax({
            url: '/configuration/statistiques/publish',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.error)
                    setSuccessMessage(response.error, '#modal-form-alert-errors');
                if(response.success)
                    setSuccessMessage(response.success, '#modal-form-alert-success');
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                getStatisticsData();
            },
            error: function(xhr) {
                var errors = []
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    stylingErrors(xhr.responseJSON.errors);
                    var datas = Object.entries(xhr.responseJSON.errors);
                    errors = datas.map(error => error[1][0]);
                } else {
                    setSuccessMessage('Erreur inconue' , '#modal-form-alert-errors');
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }
    // When publish stats on wsite
    $(document).on('click','.spinner-submit-statspublish-form-button', function() {
        stats('.spinner-submit-statspublish-form-button');
    });
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
            url : "/configuration/statistiques/trimestres",
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