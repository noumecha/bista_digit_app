/*function showSpinner(button) {
    const spinner = button.querySelector('.spinner-border');
    spinner.classList.remove('d-none');
    button.disabled = true;

    console.log(button.closest('form'));
    setTimeout(() => {
        button.closest('form').submit();
    }, 3000);
}*/

$(function(){
    $(document).on('click','.spinner-submit-button', function() {
        //console.log($(this).children('span.spinner-border'));
        $(this).children('span.spinner-border').removeClass('d-none');
        $(this).disabled = true;
        setTimeout(() => {
            $(this).closest('form').trigger('submit');
        }, 3000);
    });
});