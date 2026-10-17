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
    const zoomSource = productGallery.querySelector('[data-zoom-source]');
    const zoomLens = productGallery.querySelector('[data-zoom-lens]');
    const zoomPreview = productGallery.querySelector('[data-zoom-preview]');
    const detailGrid = zoomSource?.closest('.detail-grid');
    const zoomFactor = 2.5;
    let pointerPosition = null;

    const updateZoom = () => {
        if (!mainImage || !zoomSource || !zoomLens || !zoomPreview || !pointerPosition
            || !mainImage.naturalWidth || !mainImage.naturalHeight) return;

        if (!detailGrid?.classList.contains('is-zoom-active')) {
            detailGrid?.classList.add('is-zoom-active');
            requestAnimationFrame(updateZoom);
            return;
        }

        const imageBounds = mainImage.getBoundingClientRect();
        const sourceBounds = zoomSource.getBoundingClientRect();
        const previewBounds = zoomPreview.getBoundingClientRect();
        if (!imageBounds.width || !imageBounds.height || !previewBounds.width || !previewBounds.height) return;

        const renderedScale = Math.max(
            imageBounds.width / mainImage.naturalWidth,
            imageBounds.height / mainImage.naturalHeight,
        );
        const renderedWidth = mainImage.naturalWidth * renderedScale;
        const renderedHeight = mainImage.naturalHeight * renderedScale;
        const imageOffsetX = (imageBounds.width - renderedWidth) / 2;
        const imageOffsetY = (imageBounds.height - renderedHeight) / 2;
        const lensWidth = previewBounds.width / zoomFactor;
        const lensHeight = previewBounds.height / zoomFactor;
        const pointerX = Math.min(Math.max(pointerPosition.x - imageBounds.left, 0), imageBounds.width);
        const pointerY = Math.min(Math.max(pointerPosition.y - imageBounds.top, 0), imageBounds.height);
        const lensX = Math.min(Math.max(pointerX - lensWidth / 2, 0), imageBounds.width - lensWidth);
        const lensY = Math.min(Math.max(pointerY - lensHeight / 2, 0), imageBounds.height - lensHeight);
        const sourceX = lensX + (sourceBounds.width - imageBounds.width) / 2 - imageOffsetX;
        const sourceY = lensY + (sourceBounds.height - imageBounds.height) / 2 - imageOffsetY;

        zoomLens.style.width = `${lensWidth}px`;
        zoomLens.style.height = `${lensHeight}px`;
        zoomLens.style.left = `${lensX + (imageBounds.left - sourceBounds.left)}px`;
        zoomLens.style.top = `${lensY + (imageBounds.top - sourceBounds.top)}px`;
        zoomPreview.style.backgroundImage = `url("${mainImage.currentSrc || mainImage.src}")`;
        zoomPreview.style.backgroundSize = `${renderedWidth * zoomFactor}px ${renderedHeight * zoomFactor}px`;
        zoomPreview.style.backgroundPosition = `${-sourceX * zoomFactor}px ${-sourceY * zoomFactor}px`;
        zoomSource.classList.add('is-zoom-active');
        zoomSource.parentElement?.classList.add('is-zoom-active');
    };

    const stopZoom = () => {
        pointerPosition = null;
        detailGrid?.classList.remove('is-zoom-active');
        zoomSource?.classList.remove('is-zoom-active');
        zoomSource?.parentElement?.classList.remove('is-zoom-active');
    };

    if (zoomSource && zoomLens && zoomPreview
        && window.matchMedia('(hover: hover) and (pointer: fine) and (min-width: 1000px)').matches) {
        zoomSource.addEventListener('pointermove', (event) => {
            pointerPosition = { x: event.clientX, y: event.clientY };
            updateZoom();
        });
        zoomSource.addEventListener('pointerleave', stopZoom);
        mainImage?.addEventListener('load', updateZoom);
    }

    thumbnails.forEach((thumbnail) => {
        thumbnail.addEventListener('click', () => {
            if (!mainImage) return;

            stopZoom();
            mainImage.src = thumbnail.dataset.galleryImage;
            thumbnails.forEach((image) => {
                image.setAttribute('aria-pressed', String(image === thumbnail));
            });
        });
    });
}
