document.addEventListener('DOMContentLoaded',function(){
    var message = document.getElementById('success-message');
    if (message) {
        setTimeout(function() {
            message.style.opacity = 0;
            setTimeout(function () {
                message.style.display = 'none';
            }, 500);
        }, 4000);
    }
})
