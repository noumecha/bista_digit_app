let currentDropedElement = null;

function showDropdown(button) {
    const ddowContainer = button.querySelector('.ddown-items-container');
    if (currentDropedElement && currentDropedElement !== ddowContainer) {
        currentDropedElement.classList.add('d-none');
    }
    ddowContainer.classList.toggle('d-none');

    currentDropedElement = ddowContainer.classList.contains('d-none') ? null : ddowContainer;
}

console.log(currentDropedElement);

document.addEventListener('click', function(e) {
    if (currentDropedElement && !e.target.closest('.ddown-menu')) {
        currentDropedElement.classList.add('d-none');
        currentDropedElement = null;
    }
});