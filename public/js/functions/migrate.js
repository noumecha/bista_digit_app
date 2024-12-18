$(function(){
    $(document).on('click','.spinner-submit-modal-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        var form_datas = $(this).closest('form').serialize();
        var form_method = $(this).closest('form').prop('method');
        var form_action = $(this).closest('form').prop('action');
        var modal_id = $(this).closest('div.modal').prop('id');
        var personnel_id = $(this).data('personnel-id');
        $.ajax({
            url: form_action,
            type: form_method,
            data: form_datas,
            success: function(response) {
                if(response.error)
                    setSuccessMessage(response.error, '#modal-alert-errors-'+personnel_id);
                if(response.success)
                    setSuccessMessage(response.success, '#modal-alert-success-'+personnel_id);
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
            },
            error: function(xhr) {
                var errors = []
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    var datas = Object.entries(xhr.responseJSON.errors);
                    errors = datas.map(error => error[1][0]);
                    $('#'+modal_id).on('hidden.bs.modal', function() {
                        return false;
                    });
                } else {
                    setSuccessMessage('Erreur inconue' , 'modal-alert-errors');
                }
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 4000);
                setSuccessMessage(errors, 'modal-alert-errors');
            }
        });
    });

    // success function
    function setSuccessMessage(msg, id) {
        var msgBlock = $(id);
        msgBlock.stop(true, true);
        msgBlock.empty();
        if (Array.isArray(msg)) {
            var list = $('<ul></ul>');
            msg.forEach(function(m) {
                var items = $('<li></li>').text(m);
                list.append(items);
            });
            msgBlock.append(list);
        } else {
            msgBlock.append($('<p class="text-center"></p>').text(msg));
        }
        msgBlock.fadeIn().css('display', 'block');
        setTimeout(function() {
            msgBlock.fadeOut();
        }, 3000);
    }
});