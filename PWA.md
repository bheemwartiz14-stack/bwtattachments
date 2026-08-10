# PWA — BWT Attachments

This document explains how the Progressive Web App functionality is implemented,
how to develop locally, and how to deploy it in production.

## Architecture

The application keeps the existing **Laravel + Blade** architecture. PWA support is
added with minimal, native browser technology — no JavaScript framework migration
and no PWA-specific npm package.

```
Laravel + Blade
      |
      +-- public/site.webmanifest  (installable app metadata + icons)
      +-- public/sw.js             (service worker: caching & offline)
      +-- public/offline.html      (offline fallback page)
      +-- resources/js/pwa.js      (SW registration, online/offline UI, install prompt)
      +-- resources/views/components/layouts/base.blade.php  (manifest/meta links)
```

## Files

| File | Purpose |
| --- | --- |
| `public/site.webmanifest` | Web App Manifest (name, icons, colors, start_url, scope). |
| `public/sw.js` | Service worker with versioned caching + offline fallback. |
| `public/offline.html` | Shown when an online page is unreachable while offline. |
| `resources/js/pwa.js` | Registers the SW, toggles the offline indicator, wires the install button. |
| `resources/views/components/layouts/base.blade.php` | Adds manifest link, `theme-color`, Apple meta, PWA assets, indicator + install button. |
| `vite.config.js` | Adds `resources/js/pwa.js` to the Vite build. |

## Local development

- Service workers require a **secure context**. `http://localhost` and
  `http://127.0.0.1` are treated as secure, so local development works.
- Build the frontend assets: `npm run build` (or `npm run dev`).
- Bump the SW version: edit `CACHE_VERSION` in `public/sw.js` whenever built assets change.
- Test offline in DevTools → **Application → Service Workers** (enable "Offline")
  or **Network → Offline**.

## Cache strategy (`public/sw.js`)

- **Static assets** (css, js, images, fonts, icons): Cache-First, populated on first
  successful fetch.
- **Navigation / HTML pages**: Network-First, falling back to the last cached copy,
  then to `offline.html`.
- **API, auth, and sensitive endpoints** (`/api/*`, `/login`, `/logout`, `/register`,
  `/forgot-password`, `/password`, Livewire internal): **never cached** — network only.
- **POST / PUT / PATCH / DELETE**: never cached.
- **Security**: authentication tokens, CSRF, private responses, and financial data
  are never written to the cache.

## Updating the service worker / clearing caches

1. Change the built assets (run `npm run build`).
2. Bump `CACHE_VERSION` in `public/sw.js`.
3. Deploy. On the next visit the new SW installs and, on activation, deletes all old
   `bwt-attachments-*` caches.
4. Users can force an update in DevTools → **Application → Service Workers → Update**.

## Offline behavior

- The app is designed to work **online-first**. Most functionality requires a
  connection.
- Only assets needed to display the shell and previously-visited static content are
  available offline.
- A non-intrusive "You're offline" pill appears at the bottom-left when the network
  drops.
- A basic `offline.html` page is served when a page cannot be loaded offline.
- **No offline create/edit sync (IndexedDB) is implemented.** The business features
  (quotations, orders, admin) are online-transactional and must not write to a
  cache. If offline-capable forms become a requirement, add a sync queue backed by
  IndexedDB with idempotency keys.

## Install prompt

- When the browser fires `beforeinstallprompt`, a small **Install App** button appears
  at the bottom-right. It does not nag and respects the user's choice.
- On iOS, users add to the Home Screen via Safari's Share menu (Apple does not fire
  `beforeinstallprompt`).

## HTTPS requirement (production)

PWA service workers are generally only allowed on HTTPS. Production must serve the
app over HTTPS (e.g. behind a TLS-terminating proxy). Set `APP_URL`/`ASSET_URL` to the
HTTPS origin so the manifest and SW URLs are correct.

## Browser support

- Chrome / Edge / Android: full install + offline support.
- Safari / iOS: can "Add to Home Screen"; SW + offline support is partial and varies
  by iOS version.
- Older browsers without Service Worker support degrade gracefully — the app simply
  behaves as a normal website.
