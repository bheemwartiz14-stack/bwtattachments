/**
 * BWT Attachments — PWA client logic
 *
 * Responsibilities:
 *  - Register the service worker (secure contexts only).
 *  - Show a non-intrusive online/offline indicator.
 *  - Wire the install button to the browser `beforeinstallprompt` event.
 */
(function () {
    'use strict';

    const indicator = document.getElementById('offline-indicator');
    const indicatorText = document.getElementById('offline-indicator-text');
    const installButton = document.getElementById('pwa-install-button');
    let deferredPrompt = null;
    let offlineSeen = false;

    /* ---------------- Service Worker registration ---------------- */
    if ('serviceWorker' in navigator && (window.isSecureContext || location.hostname === 'localhost' || location.hostname === '127.0.0.1')) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/sw.js').catch(function (err) {
                console.warn('Service worker registration failed:', err);
            });
        });
    }

    /* ---------------- Online / Offline indicator ---------------- */
    function isOnline() {
        return typeof navigator.onLine === 'boolean' ? navigator.onLine : true;
    }

    function updateStatus() {
        if (!indicator) return;
        const online = isOnline();
        if (online) {
            indicator.classList.add('hidden');
            indicator.classList.remove('flex');
            offlineSeen = false;
            return;
        }
        offlineSeen = true;
        indicator.classList.remove('hidden');
        indicator.classList.add('flex');
        if (indicatorText) {
            indicatorText.textContent =
                'You\'re offline — some pages may not be available until you reconnect.';
        }
    }

    window.addEventListener('online', updateStatus);
    window.addEventListener('offline', updateStatus);
    document.addEventListener('livewire:navigated', updateStatus);
    updateStatus();

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

    /* Expose state for debugging / potential custom UI. */
    window.__bwtPWA = {
        isOnline: isOnline,
        offlineSeen: function () {
            return offlineSeen;
        },
    };
})();
