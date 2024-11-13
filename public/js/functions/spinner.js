function showSpinner(button) {
    const spinner = button.querySelector('.spinner-border');
    spinner.classList.remove('d-none');
    button.disabled = true;

    setTimeout(() => {
        button.closest('form').submit();
    }, 3000);
}