togglePasswordVisibility('#icon-con', '#password-con');
togglePasswordVisibility('#icon-pwd', '#password');
togglePasswordVisibility('#icon-confirm', '#confirmPassword');

function togglePasswordVisibility(icon,el) {
    $(icon).on('click', function() {
        if($(el).attr('type') === "password") {
            $(el).attr('type', 'text')
            $(icon).addClass('fa-eye-slash')
            $(icon).removeClass('fa-eye')
        } else {
            $(el).attr('type', 'password')
            $(icon).removeClass('fa-eye-slash')
            $(icon).addClass('fa-eye')
        }
    });
}