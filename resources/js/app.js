import './bootstrap';

const themeKey = 'kj-theme';
const savedTheme = window.localStorage.getItem(themeKey);
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
document.documentElement.dataset.theme = savedTheme || (prefersDark ? 'dark' : 'light');

document.querySelectorAll('.theme-toggle').forEach((toggle) => {
    toggle.addEventListener('click', () => {
        const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
        document.documentElement.dataset.theme = nextTheme;
        window.localStorage.setItem(themeKey, nextTheme);
    });
});

const menuToggle = document.querySelector('.menu-toggle');
const storeNavigation = document.querySelector('#store-navigation');

if (menuToggle && storeNavigation) {
    menuToggle.addEventListener('click', () => {
        const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!isOpen));
        storeNavigation.classList.toggle('is-open', !isOpen);
    });

    storeNavigation.addEventListener('click', (event) => {
        if (event.target instanceof HTMLAnchorElement) {
            menuToggle.setAttribute('aria-expanded', 'false');
            storeNavigation.classList.remove('is-open');
        }
    });
}
