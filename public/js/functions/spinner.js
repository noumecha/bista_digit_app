function showSpinner(button) {
    // Show the spinner and disable the button
    const spinner = button.querySelector('.spinner-border');
    spinner.classList.remove('d-none');
    button.disabled = true;

    // Wait for 5 seconds before submitting the form
    setTimeout(() => {
        button.closest('form').submit();
    }, 3000);
}