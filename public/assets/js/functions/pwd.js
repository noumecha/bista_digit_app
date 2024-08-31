function generatePassword() {
    var l = 12;
    var charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    var password = '';
    for (var i = 0, n = charset.length ; i < l; i++) {
        password += charset.charAt(Math.floor(Math.random() * n));
    }
    return password;
}
document.getElementById('password').setAttribute("value", generatePassword());
//console.log(generatePassword());
