$(function() {
    // on form submit
    $('#actusDataSearch').on('submit', function(e) {
        e.preventDefault();
        var dataElement = $('#actusDatas');
        var spinner = $('#loader').show();
        spinner.removeClass('d-none');
        dataElement.addClass('d-none');
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            url : "/actualites",
            type : 'GET',
            data : formData,
            success : function(data) {
                dataElement.html(data);
                setTimeout(function() {
                    spinner.addClass('d-none');
                    dataElement.removeClass('d-none');
                }, 4000);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    })
    // fetching data defaultly
    fetchDatas();

    // fetching all actualites :
    function fetchDatas() {
        var spinner = $('#loader').show();
        var dataElement = $('#actusDatas');
        spinner.removeClass('d-none');
        dataElement.addClass('d-none');
        $.ajax({
            url : "/actualites",
            type : 'GET',
            success : function(data) {
                dataElement.html(data);
                setTimeout(function() {
                    spinner.addClass('d-none');
                    dataElement.removeClass('d-none');
                }, 4000);
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                setSuccessMessage(errors, '#modal-form-alert-errors');
            }
        });
    }
})