$(function() {
    // fetching obc stats dynamically
    $('#annee_scolaire_id').on('change keyup', function () {
        fetchObc();
    });
    // default data :
    fetchObc();

    // fetching all bulletins :
    function fetchObc() {
        var formData = $('#obcFilterForm').serialize();
        var spinner = $('#obcLoader').show();
        var dataElement = $('#obcRanksTable');
        spinner.removeClass('d-none');
        dataElement.addClass('d-none');
        $.ajax({
            url : "/statistiques",
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
    }
    // on form submit for trims datas
    $('#statsDataSearch').on('submit', function(e) {
        e.preventDefault();
        var dataElement = $('#statsDatas');
        var spinner = $('#loader').show();
        spinner.removeClass('d-none');
        dataElement.addClass('d-none');
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            url : "/statistiques",
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
    // fetching stats onload
    fetchDatas();

    // fetching all stats :
    function fetchDatas() {
        var spinner = $('#loader').show();
        var dataElement = $('#statsDatas');
        spinner.removeClass('d-none');
        dataElement.addClass('d-none');
        $.ajax({
            url : "/statistiques",
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