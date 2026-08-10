/**
 * BWT Attachments — PWA client logic
 *
 * Online-only PWA. Responsibilities:
 *  - Register the service worker (secure contexts only).
 *  - Wire the install button to the browser `beforeinstallprompt` event.
 */
(function () {
    'use strict';

    const installButton = document.getElementById('pwa-install-button');
    let deferredPrompt = null;

    /* ---------------- Service Worker registration ---------------- */
    if ('serviceWorker' in navigator && (window.isSecureContext || location.hostname === 'localhost' || location.hostname === '127.0.0.1')) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/public/sw.js').catch(function (err) {
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
                if (choice.outcome === 'accepted') {
                    installButton.classList.add('hidden');
                }
            });
        });
    }
})();
