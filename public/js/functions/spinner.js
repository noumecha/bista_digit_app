$(function(){
    $(document).on('click','.spinner-submit-button', function() {
        showSpinner($(this));
    });

    function showSpinner(el) {
        el.children('span.spinner-border').removeClass('d-none');
        el.disabled = true;
        setTimeout(function() {
            el.children('span.spinner-border').addBack('d-none');
        }, 3000);
        el.closest('form').submit();
    }
});