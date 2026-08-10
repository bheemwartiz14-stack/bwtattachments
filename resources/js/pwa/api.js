/**
 * Lightweight API client for the offline-first PWA.
 *
 * Uses a Sanctum bearer token held in memory and mirrored to IndexedDB so the
 * user stays authenticated across reloads while offline. The token is stored in
 * IndexedDB only (never in the service worker cache or localStorage).
 */
import { getMeta, setMeta } from './db';

const TOKEN_KEY = 'auth_token';

let token = null;

export async function loadStoredToken() {
    token = token || (await getMeta(TOKEN_KEY)) || null;
    return token;
}

export function setToken(value) {
    token = value || null;
    if (value) setMeta(TOKEN_KEY, value);
    else getMeta(TOKEN_KEY).then((v) => v && setMeta(TOKEN_KEY, null));
}

export function getToken() {
    return token;
}

export async function api(path, options = {}) {
    const headers = { Accept: 'application/json', ...(options.headers || {}) };
    if (options.body && !(options.body instanceof FormData)) {
        headers['Content-Type'] = 'application/json';
    }
    if (token) headers.Authorization = `Bearer ${token}`;

    const res = await fetch(`/api${path}`, { ...options, headers });

    const isJson = res.headers.get('content-type')?.includes('application/json');
    const body = isJson ? await res.json() : await res.text();

    if (!res.ok) {
        const err = new Error(body.message || `Request failed (${res.status})`);
        err.status = res.status;
        err.body = body;
        throw err;
    }
    return body;
}

export async function login(email, password) {
    const res = await api('/login', {
        method: 'POST',
        body: JSON.stringify({ email, password }),
    });
    setToken(res.token);
    return res;
}

export async function logout() {
    try {
        await api('/logout', { method: 'POST' });
    } catch (e) {
        // ignore network errors during logout
    }
    setToken(null);
}

export async function me() {
    const res = await api('/me');
    return res.user;
}

export async function bootstrap() {
    return api('/sync/bootstrap');
}

export async function pull(since) {
    const q = since ? `?since=${encodeURIComponent(since)}` : '';
    return api(`/sync/pull${q}`);
}

export async function push(operations) {
    return api('/sync/push', {
        method: 'POST',
        body: JSON.stringify({ operations }),
    });
}

export async function ack(operationUuids) {
    return api('/sync/ack', {
        method: 'POST',
        body: JSON.stringify({ operation_uuids: operationUuids }),
    });
}
