document.addEventListener('DOMContentLoaded',function(){
    var message = document.querySelectorAll('.success-message');
    var toast = document.querySelectorAll('.toast-block');
    // simple alert success
    if (message) {
        message.forEach(msg => {
            setTimeout(function() {
                msg.style.opacity = 0;
                setTimeout(function () {
                    msg.style.display = 'none';
                }, 500);
            }, 4000);
        });
    }
    // for the toast
    if (toast) {
        toast.forEach(t => {
            t.style.display = 'flex';
            setTimeout(function() {
                t.style.display = 'none';
            }, 4000);
        });
    }
})
