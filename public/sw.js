const CACHE_NAME = 'krishoker-jhuri-v1';
const OFFLINE_URL = '/offline.html';
const PRECACHE_URLS = [
    OFFLINE_URL,
    '/manifest.webmanifest',
    '/images/pwa-icon-192.png',
    '/images/pwa-icon-512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(PRECACHE_URLS))
            .then(() => self.skipWaiting()),
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(
                keys
                    .filter((key) => key.startsWith('krishoker-jhuri-') && key !== CACHE_NAME)
                    .map((key) => caches.delete(key)),
            ))
            .then(() => self.clients.claim()),
    );
});

self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    if (request.method !== 'GET' || url.origin !== self.location.origin) {
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(async () => {
                const offlinePage = await caches.match(OFFLINE_URL);

                return offlinePage || Response.error();
            }),
        );
        return;
    }

    const isPublicAsset = ['/build/', '/fonts/', '/images/'].some((prefix) => url.pathname.startsWith(prefix))
        || url.pathname === '/manifest.webmanifest'
        || url.pathname === OFFLINE_URL;

    if (!isPublicAsset) {
        return;
    }

    event.respondWith(
        caches.match(request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(request).then((response) => {
                if (response.ok && response.type === 'basic') {
                    const responseToCache = response.clone();

                    event.waitUntil(
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, responseToCache)),
                    );
                }

                return response;
            });
        }),
    );
});
