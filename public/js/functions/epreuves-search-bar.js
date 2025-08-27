$(function() {
    // on form submit
    $('#epreuvesDataSearch').on('submit', function(e) {
        e.preventDefault();
        var dataElement = $('#epreuveDatas');
        var spinner = $('#epreuve-loader').show();
        spinner.removeClass('d-none');
        dataElement.addClass('d-none');
        var form = $(this);
        var formData = form.serialize();
        $.ajax({
            url : "/epreuves",
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
        var spinner = $('#epreuve-loader').show();
        var dataElement = $('#epreuveDatas');
        spinner.removeClass('d-none');
        dataElement.addClass('d-none');
        $.ajax({
            url : "/epreuves",
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
    
    // handle pagination :
    $(document).on('click', '.pagination a', function (event) {
        event.preventDefault();

        var page = $(this).attr('href').split('page=')[1];
        fetchPage(page, '#epreuveDatas');
    });
})