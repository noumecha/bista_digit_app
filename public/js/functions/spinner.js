$(function(){
    $(document).on('click','.spinner-submit-button', function() {
        $(this).children('span.spinner-border').removeClass('d-none');
        $(this).disabled = true;
        var form_datas = $(this).closest('form').serialize();
        var form_method = $(this).closest('form').prop('method');
        var form_action = $(this).closest('form').prop('action');
        var modal_id = $(this).closest('div.modal').prop('id');
        //console.log($('#'+modal_id));
        setTimeout(() => {
            $.ajax({
                url: form_action,
                type: form_method,
                data: form_datas,
                success: function(response) {
                    console.log(JSON.stringify(response));
                    setTimeout(function() {
                        setSuccessMessage(response.success, '#msg');
                    }, 3000);
                    setTimeout(function() {
                        $(this).children('span.spinner-border').addBack('d-none');
                        $('#'+modal_id).on('hidden.bs.modal', function() {
                            return true;
                        });
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    var datas = Object.entries(xhr.responseJSON.errors);
                    var errors = datas.map(error => error[1][0]);
                    $('#'+modal_id).on('hidden.bs.modal', function() {
                        return false;
                    });
                    setTimeout(function() {
                        $(this).children('span.spinner-border').addBack('d-none');
                    }, 3000);
                    setSuccessMessage(errors, '#errors');
                    console.log("errors : ", errors);
                }
            });
        }, 3000);
    });

    // success function
    function setSuccessMessage(msg, id) {
        var message = $(id);
        message.stop(true, true);
        message.empty();
        if (Array.isArray(msg)) {
            var list = $('<ul class="list-group text-left"></ul>');
            msg.forEach(function(m) {
                var items = $('<li class="list-group-item list-group-item-danger"></li>').text(m);
                list.append(items);
            });
            message.append(list);
        } else {
            var text = $('<p class="text-center"></p>').text(msg);
            message.append(text);
        }
        message.fadeIn().css('display', 'block');
        setTimeout(function() {
            message.fadeOut();
        }, 3000);
    }
});