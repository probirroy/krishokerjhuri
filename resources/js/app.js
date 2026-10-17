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
const headerInner = storeNavigation?.closest('.header-inner');

const selectNavigationLink = (selectedLink) => {
    storeNavigation.querySelectorAll('a').forEach((link) => {
        const isSelected = link === selectedLink;
        link.classList.toggle('active', isSelected);
        link.classList.toggle('is-selected', isSelected);
    });
};

if (menuToggle && storeNavigation) {
    const currentHashLink = [...storeNavigation.querySelectorAll('a')].find((link) => {
        const url = new URL(link.href);
        return url.pathname === window.location.pathname && url.hash === window.location.hash && url.hash;
    });

    if (currentHashLink) {
        selectNavigationLink(currentHashLink);
    }

    menuToggle.addEventListener('click', () => {
        const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', String(!isOpen));
        storeNavigation.classList.toggle('is-open', !isOpen);
        headerInner?.classList.toggle('navigation-open', !isOpen);
    });

    storeNavigation.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) return;

        const link = event.target.closest('a');
        if (!link) return;

        selectNavigationLink(link);
        menuToggle.setAttribute('aria-expanded', 'false');
        storeNavigation.classList.remove('is-open');
        headerInner?.classList.remove('navigation-open');
    });
}

const search = document.querySelector('.header-search');
const searchToggle = document.querySelector('.header-search-toggle');

if (search && searchToggle) {
    searchToggle.addEventListener('click', () => {
        const isOpen = search.classList.toggle('is-open');
        searchToggle.setAttribute('aria-expanded', String(isOpen));
        if (isOpen) {
            search.querySelector('input[name="q"]')?.focus();
        }
    });
}

const productGallery = document.querySelector('[data-product-gallery]');

if (productGallery) {
    const mainImage = productGallery.querySelector('[data-gallery-main]');
    const thumbnails = productGallery.querySelectorAll('[data-gallery-image]');

    thumbnails.forEach((thumbnail) => {
        thumbnail.addEventListener('click', () => {
            if (!mainImage) return;

            mainImage.src = thumbnail.dataset.galleryImage;
            thumbnails.forEach((image) => {
                image.setAttribute('aria-pressed', String(image === thumbnail));
            });
        });
    });
}
