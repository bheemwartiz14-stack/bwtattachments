/**
 * Network status helper — tracks online/offline state and dispatches events.
 */
export function initNetworkStatus({ onStatusChange, onSyncRequest }) {
    let online = navigator.onLine;

    const notify = (value) => {
        online = value;
        if (typeof onStatusChange === 'function') onStatusChange(value);
    };

    window.addEventListener('online', () => {
        notify(true);
        if (typeof onSyncRequest === 'function') onSyncRequest();
    });
    window.addEventListener('offline', () => notify(false));

    // fire once so the UI initialises correctly
    notify(online);

    return {
        isOnline: () => online,
        get current() {
            return online;
        },
    };
}
