document.addEventListener('DOMContentLoaded',function(){
    var message = document.querySelectorAll('.success-message');
    //var message = document.getElementById('success-message');
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
})
