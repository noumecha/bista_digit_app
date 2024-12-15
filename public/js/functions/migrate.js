$(function(){
    $(document).on('click','.spinner-submit-modal-button', function() {
        var spinner = $(this).children('span.spinner-border');
        spinner.removeClass('d-none');
        $(this).disabled = true;
        var form_datas = $(this).closest('form').serialize();
        var form_method = $(this).closest('form').prop('method');
        var form_action = $(this).closest('form').prop('action');
        var modal_id = $(this).closest('div.modal').prop('id');
        var show_error_msg_block = $(this).closest('#modal-alert-success')
        $.ajax({
            url: form_action,
            type: form_method,
            data: form_datas,
            success: function(response) {
                if(response.error)
                    setSuccessMessage(response.error, show_error_msg_block);
                if(response.success)
                    setSuccessMessage(response.success, '#modal-alert-success');
                spinner.addClass('d-none');
            },
            error: function(xhr, status, error) {
                var datas = Object.entries(xhr.responseJSON.errors);
                var errors = datas.map(error => error[1][0]);
                $('#'+modal_id).on('hidden.bs.modal', function() {
                    return false;
                });
                setTimeout(function() {
                    spinner.addClass('d-none');
                }, 1000);
                setSuccessMessage(errors, '#modal-alert-errors');
            }
        });
    });

    // success function
    function setSuccessMessage(msg, id) {
        var msgBlock = $(id);
        msgBlock.removeClass('d-none')
        console.log(msgBlock.attr('class'));
        /*msgBlock.stop(true, true);
        msgBlock.empty();
       if (Array.isArray(msg)) {
            var list = $('<ul class="list-group text-left"></ul>');
            msg.forEach(function(m) {
                var items = $('<li class="list-group-item list-group-item-danger"></li>').text(m);
                list.append(items);
            });
            msgBlock.append(list);
        } else {
            //var text = $('<p class="text-center"></p>').text(msg);
            msgBlock.append($('<p class="text-center"></p>').text(msg));
        }
        msgBlock.fadeIn().css('display', 'block');
        setTimeout(function() {
            msgBlock.fadeOut();
        }, 3000);*/
    }
});