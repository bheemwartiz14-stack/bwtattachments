/**
 * BWT Attachments — PWA client logic (offline-first)
 *
 * Responsibilities:
 *  - Register the service worker (secure contexts only).
 *  - Wire the install button to the browser `beforeinstallprompt` event.
 *  - Restore the Sanctum token, sync on startup/online, and render the
 *    offline/sync UI.
 */
import * as api from './pwa/api';
import { initNetworkStatus } from './pwa/network-status';
import { initSyncUi, refresh, syncStarted, syncFinished } from './pwa/sync-ui';
import { sync, isOnline, enqueue } from './pwa/sync-manager';

(function () {
    'use strict';

    const installButton = document.getElementById('pwa-install-button');
    let deferredPrompt = null;

    /* ---------------- Service Worker registration ---------------- */
    const secure = window.isSecureContext || ['localhost', '127.0.0.1'].includes(location.hostname);
    if ('serviceWorker' in navigator && secure) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js').catch(function (err) {
                console.warn('Service worker registration failed:', err);
            });
        });
    }

    /* ---------------- Install prompt ---------------- */
    window.addEventListener('beforeinstallprompt', function (event) {
        event.preventDefault();
        deferredPrompt = event;
        if (installButton) installButton.classList.remove('hidden');
    });

    window.addEventListener('appinstalled', function () {
        deferredPrompt = null;
        if (installButton) installButton.classList.add('hidden');
    });

    if (installButton) {
        installButton.addEventListener('click', function () {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function (choice) {
                deferredPrompt = null;
                if (choice.outcome === 'accepted') installButton.classList.add('hidden');
            });
        });
    }

    /* ---------------- Offline/sync lifecycle ---------------- */
    initSyncUi();

    async function runSync() {
        syncStarted();
        try {
            await sync();
        } catch (e) {
            console.warn('Sync failed:', e);
        } finally {
            syncFinished();
        }
    }

    // Restore token + attempt an initial sync once the page is usable.
    window.addEventListener('load', async function () {
        await api.loadStoredToken();
        await refresh();
        if (api.getToken() && isOnline()) runSync();
    });

    initNetworkStatus({
        onStatusChange: () => {},
        onSyncRequest: () => {
            if (api.getToken()) runSync();
        },
    });

    // Expose a hook for the app to check connectivity and enqueue offline ops.
    window.BWTPWA = { api, sync, syncNow: runSync, enqueue, isOnline };
})();
