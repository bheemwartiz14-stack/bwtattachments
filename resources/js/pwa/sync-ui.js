/**
 * Sync/offline UI — a small floating indicator showing connectivity, pending
 * operations and a manual sync trigger. Injected into the page; safe to run
 * even when the service worker is not available.
 */
import { getPendingCount, sync, isOnline } from './sync-manager';

let container = null;

function ensureContainer() {
    if (container) return container;
    container = document.createElement('div');
    container.id = 'bwt-sync-ui';
    container.style.cssText = [
        'position:fixed',
        'right:16px',
        'bottom:16px',
        'z-index:9999',
        'display:flex',
        'align-items:center',
        'gap:8px',
        'font:600 12px/1 system-ui, sans-serif',
        'border-radius:999px',
        'padding:6px 12px',
        'box-shadow:0 2px 8px rgba(0,0,0,.18)',
        'cursor:pointer',
        'user-select:none',
    ].join(';');
    container.addEventListener('click', () => {
        if (isOnline()) {
            setState('syncing');
            sync()
                .then(() => refresh())
                .catch(() => refresh());
        }
    });
    document.body.appendChild(container);
    return container;
}

function setState({ online, pending, syncing }) {
    const el = ensureContainer();
    const dot = syncing ? '⟳' : online ? '●' : '○';
    const label = syncing ? 'Syncing…' : pending > 0 ? `${pending} pending` : online ? 'Online' : 'Offline';
    el.textContent = `${dot} ${label}`;
    el.style.background = syncing ? '#e0b400' : online ? (pending ? '#2f7d32' : '#1c6bb0') : '#b3261e';
    el.style.color = '#fff';
}

export async function refresh() {
    const pending = await getPendingCount();
    setState({ online: isOnline(), pending, syncing: false });
}

export function initSyncUi() {
    ensureContainer();
    setState({ online: isOnline(), pending: 0, syncing: false });
    window.addEventListener('online', refresh);
    window.addEventListener('offline', refresh);
    window.addEventListener('bwt:sync-changed', refresh);
    window.addEventListener('bwt:auth-expired', () => {
        setState({ online: true, pending: 0, syncing: false });
    });
}

export function syncStarted() {
    setState({ online: isOnline(), pending: 0, syncing: true });
}

export function syncFinished() {
    refresh();
}
