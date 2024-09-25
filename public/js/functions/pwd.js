function generatePassword() {
    var l = 12;
    var charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    var password = '';
    for (var i = 0, n = charset.length ; i < l; i++) {
        password += charset.charAt(Math.floor(Math.random() * n));
    }
    return password;
}
el = document.getElementById('password');
if (el) {
    el.setAttribute("value", generatePassword());
}
