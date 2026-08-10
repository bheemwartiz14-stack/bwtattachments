/*
 * BWT Attachments — Service Worker
 *
 * Online-only PWA.
 *
 * This service worker exists so the app is installable as a PWA, but it does
 * NOT cache any content. All requests go straight to the network (no offline
 * support). The only thing it does is claim clients on activation and purge
 * any caches created by older versions.
 */
const CACHE_VERSION = 'bwt-attachments-online-only';

self.addEventListener('install', () => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) => Promise.all(keys.filter((key) => key.startsWith('bwt-attachments-')).map((key) => caches.delete(key))))
            .then(() => self.clients.claim())
    );
});

// Network-only: never serve from cache, never write to cache.
self.addEventListener('fetch', (event) => {
    event.respondWith(fetch(event.request));
});
