function toggleDropdownMenu(el) {
    const menu = el.nextElementSibling;
    const isOpen = menu.classList.contains('show');

    document.querySelectorAll('.dropdown-menu.visible').forEach(m => closeMenu(m));

    if (!isOpen) {
        openMenu(menu);
    }
}

function openMenu(menu) {
    menu.classList.add('visible');
    requestAnimationFrame(() => {
        menu.classList.add('show');
        menu.classList.remove('hide');
    });
}

function closeMenu(menu) {
    menu.classList.remove('show');
    menu.classList.add('hide');
    setTimeout(() => {
        menu.classList.remove('visible', 'hide');
    }, 200);
}

document.addEventListener('click', function(event) {
    document.querySelectorAll('.dropdown-wrapper').forEach(wrapper => {
        if (!wrapper.contains(event.target)) {
            const menu = wrapper.querySelector('.dropdown-menu');
            if (menu && menu.classList.contains('visible')) {
                closeMenu(menu);
            }
        }
    });
});
