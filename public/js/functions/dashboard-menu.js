window.addEventListener('DOMContentLoaded', (event) => {
    const activeSubmenu = localStorage.getItem('activeSubmenu');
    if(activeSubmenu) {
        let elements = document.querySelectorAll(`[data-submenu="${activeSubmenu}"].submenu-click-item`);
        elements.forEach(element => {
            element.classList.add('toggle-click-item');
        });
    }
})

let btns = document.querySelectorAll("[data-submenu]");
btns.forEach(btn => {
  btn.addEventListener("click" , function(event) {
    if(event.target.closest('.submenu-click-link')) {
        let submenuGroup = btn.getAttribute('data-submenu');
        localStorage.setItem('activeSubmenu', submenuGroup);
        let elements = document.querySelectorAll(`[data-submenu="${submenuGroup}"].submenu-click-item`);
        elements.forEach(element => {
            element.classList.toggle('toggle-click-item');
        });
    };
  });
});