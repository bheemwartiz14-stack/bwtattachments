/*
 * BWT Attachments — Service Worker
 *
 * Security-sensitive by design:
 *  - Only GET requests are ever cached.
 *  - Authenticated, API, and auth-related endpoints are NEVER cached.
 *  - Navigation uses Network-First so users always get fresh pages when online.
 *  - Static assets use Cache-First for performance.
 *
 * Bump CACHE_VERSION whenever app assets change.
 */
const CACHE_VERSION = 'bwt-attachments-v1';
const STATIC_CACHE = CACHE_VERSION + '-static';
const OFFLINE_URL = '/offline.html';

const STATIC_EXTENSIONS = new Set([
    'css',
    'js',
    'mjs',
    'png',
    'jpg',
    'jpeg',
    'webp',
    'gif',
    'svg',
    'ico',
    'woff',
    'woff2',
    'ttf',
    'eot',
]);

// Paths that must never be cached (sensitive / private).
function isSensitive(url) {
    const path = url.pathname.toLowerCase();
    return (
        path.startsWith('/api/') ||
        path.startsWith('/login') ||
        path.startsWith('/logout') ||
        path.startsWith('/register') ||
        path.startsWith('/forgot-password') ||
        path.startsWith('/auth') ||
        path.startsWith('/password') ||
        path.includes('/csrf') ||
        path.includes('__livewire') ||
        path.startsWith('/broadcasting')
    );
}

function isStaticAsset(url) {
    const path = url.pathname;
    const dot = path.lastIndexOf('.');
    if (dot === -1) return false;
    return STATIC_EXTENSIONS.has(path.slice(dot + 1).toLowerCase());
}

async function cachePut(request, response) {
    if (response && response.ok) {
        const cache = await caches.open(STATIC_CACHE);
        cache.put(request, response.clone());
    }
    return response;
}

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches
            .open(STATIC_CACHE)
            .then((cache) => cache.addAll([OFFLINE_URL, '/site.webmanifest']))
            .then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((keys) =>
                Promise.all(
                    keys.filter((key) => key !== STATIC_CACHE && key.startsWith('bwt-attachments-')).map((key) => caches.delete(key))
                )
            )
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const request = event.request;

    // Only handle same-origin GET requests.
    if (request.method !== 'GET' || !request.url.startsWith(self.location.origin)) {
        return;
    }

    const url = new URL(request.url);

    if (isSensitive(url)) {
        // Never cache sensitive requests — hit the network directly.
        return;
    }

    // Static assets: Cache-First, populating the cache on first success.
    if (isStaticAsset(url)) {
        event.respondWith(
            caches.match(request).then((cached) => cached || fetch(request).then((res) => cachePut(request, res)))
        );
        return;
    }

    // Navigation / documents: Network-First with offline fallback.
    if (request.mode === 'navigate' || request.headers.get('accept')?.includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then((response) => cachePut(request, response))
                .catch(async () => {
                    const cached = await caches.match(request);
                    return cached || caches.match(OFFLINE_URL);
                })
        );
        return;
    }

    // Other GET requests (e.g. JSON): Network-First, no blind caching.
    event.respondWith(fetch(request).catch(() => caches.match(request)));
});
