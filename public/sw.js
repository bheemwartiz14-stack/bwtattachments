/*
 * BWT Attachments — Service Worker (offline-first)
 *
 * Strategy:
 *  - /api/* requests: network-only (auth + live data; never cached).
 *  - Navigation requests: network-first, fallback to the offline page.
 *  - Static assets (js/css/images/fonts): stale-while-revalidate so the shell
 *    works offline.
 *
 * Application data is stored in IndexedDB (see resources/js/pwa/db.js) and
 * synced through the /api/sync/* endpoints. The service worker never caches
 * IndexedDB contents or auth tokens.
 */
const CACHE_VERSION = 'bwt-attachments-v2';
const STATIC_CACHE = `bwt-static-${CACHE_VERSION}`;
const OFFLINE_URL = '/offline.html';

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches
            .open(STATIC_CACHE)
            .then((cache) => cache.addAll([OFFLINE_URL]))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(keys.filter((key) => key.startsWith('bwt-')).map((key) => key === STATIC_CACHE ? Promise.resolve() : caches.delete(key)))
            )
            .then(() => self.clients.claim())
    );
});

function isApiRequest(url) {
    return url.pathname.startsWith('/api/') || url.pathname === '/api';
}

function isNavigation(req) {
    return req.mode === 'navigate';
}

function isStaticAsset(url) {
    const ext = url.pathname.split('.').pop().toLowerCase();
    return ['js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'woff', 'woff2', 'ttf', 'ico', 'json', 'webmanifest'].includes(ext);
}

self.addEventListener('fetch', (event) => {
    const req = event.request;
    if (req.method !== 'GET') return; // let POST/PUT etc go to network

    const url = new URL(req.url);
    if (url.origin !== self.location.origin) return; // ignore cross-origin

    // 1. API — never cached.
    if (isApiRequest(url)) return;

    // 2. Navigation — network first, offline fallback.
    if (isNavigation(req)) {
        event.respondWith(
            fetch(req)
                .then((res) => {
                    // Cache the HTML shell so repeat visits work offline.
                    const copy = res.clone();
                    caches.open(STATIC_CACHE).then((cache) => cache.put(req, copy)).catch(() => {});
                    return res;
                })
                .catch(() => caches.match(OFFLINE_URL).then((c) => c || caches.match(req)))
        );
        return;
    }

    // 3. Static assets — stale-while-revalidate.
    if (isStaticAsset(url)) {
        event.respondWith(
            caches.open(STATIC_CACHE).then((cache) =>
                cache.match(req).then((cached) => {
                    const network = fetch(req)
                        .then((res) => {
                            if (res && res.status === 200) cache.put(req, res.clone());
                            return res;
                        })
                        .catch(() => cached);
                    return cached || network;
                })
            )
        );
        return;
    }

    // 4. Everything else — network, fallback to cache.
    event.respondWith(
        fetch(req).catch(() => caches.match(req).then((c) => c || caches.match(OFFLINE_URL)))
    );
});
