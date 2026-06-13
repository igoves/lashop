import initFullajax from './fullajax';

// мобильное меню
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-menu-toggle]');
    const menu = document.querySelector('[data-mobile-menu]');

    toggle?.addEventListener('click', () => {
        const hidden = menu.classList.toggle('hidden');
        toggle.setAttribute('aria-expanded', String(!hidden));
    });

    // User dropdown
    const dropdown = document.querySelector('[data-dropdown]');
    const dropdownToggle = dropdown?.querySelector('[data-dropdown-toggle]');
    const dropdownMenu = dropdown?.querySelector('[data-dropdown-menu]');

    dropdownToggle?.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu?.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
        if (dropdown && !dropdown.contains(e.target)) {
            dropdownMenu?.classList.add('hidden');
        }
    });

    initFullajax();

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js');
    }
});
