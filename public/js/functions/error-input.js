document.addEventListener('DOMContentLoaded',function() {
    var input = document.querySelectorAll('.form-control');
    if (input) {
        input.forEach(item => {
            if(item.classList.contains('is-invalid')) {
                setTimeout(function() {
                    item.classList.remove('is-invalid');
                }, 4000);
            }
        });
    }
})
