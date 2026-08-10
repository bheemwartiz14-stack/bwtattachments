# PWA — BWT Attachments

This document explains how the Progressive Web App functionality is implemented,
how to develop locally, and how to deploy it in production.

## Architecture

The application keeps the existing **Laravel + Blade** architecture. PWA support is
added with minimal, native browser technology — no JavaScript framework migration
and no PWA-specific npm package.

> **Online-only.** This PWA is **installable** but does **not** provide offline
> support. The app requires a network connection; the service worker never caches
> content.

```
Laravel + Blade
      |
      +-- public/site.webmanifest  (installable app metadata + icons)
      +-- public/sw.js             (minimal service worker: installability, no caching)
      +-- resources/js/pwa.js      (SW registration + install prompt)
      +-- resources/views/components/layouts/base.blade.php  (manifest/meta links + install button)
```

## Files

| File | Purpose |
| --- | --- |
| `public/site.webmanifest` | Web App Manifest (name, icons, colors, start_url, scope). |
| `public/sw.js` | Minimal service worker for installability. Network-only — never caches. |
| `resources/js/pwa.js` | Registers the SW and wires the install button. |
| `resources/views/components/layouts/base.blade.php` | Adds manifest link, `theme-color`, Apple meta, PWA assets, and the install button. |
| `vite.config.js` | Adds `resources/js/pwa.js` to the Vite build. |

## Local development

- Service workers require a **secure context**. `http://localhost` and
  `http://127.0.0.1` are treated as secure, so local development works.
- Build the frontend assets: `npm run build` (or `npm run dev`).
- Inspect installability in DevTools → **Application → Manifest / Service Workers**.

## Behavior

- **Installable**: the manifest + registered service worker make the app installable
  on Android, Chrome, Edge, and desktop. On iOS, users add it via Safari's Share menu.
- **Online-only**: all requests go straight to the network. The service worker never
  caches requests or responses, so there is no offline content and no stale data.
- **Install prompt**: when the browser fires `beforeinstallprompt`, a small **Install
  App** button appears at the bottom-right. It does not nag and respects the user's
  choice.

## Updating

- No cache versioning is needed because nothing is cached.
- On deployment, the new service worker installs, claims the page, and purges any
  caches left by older versions.

## HTTPS requirement (production)

PWA service workers are generally only allowed on HTTPS. Production must serve the
app over HTTPS (e.g. behind a TLS-terminating proxy). Set `APP_URL`/`ASSET_URL` to the
HTTPS origin so the manifest and SW URLs are correct.

## Browser support

- Chrome / Edge / Android: full install support.
- Safari / iOS: can "Add to Home Screen"; SW support is partial and varies by iOS version.
- Older browsers without Service Worker support degrade gracefully — the app simply
  behaves as a normal website.
