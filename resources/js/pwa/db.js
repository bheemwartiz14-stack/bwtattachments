/**
 * IndexedDB layer for the offline-first PWA.
 *
 * Stores only non-sensitive application data. Authentication tokens and other
 * secrets are held separately (see api.js) and are never put in the service
 * worker cache. Client data is never treated as authoritative — it is always
 * revalidated by the Laravel API during sync.
 */
const DB_NAME = 'bwt-attachments';
const DB_VERSION = 1;

const STORES = {
    meta: { keyPath: 'key' },
    categories: { keyPath: 'id' },
    subcategories: { keyPath: 'id' },
    connections: { keyPath: 'id' },
    products: { keyPath: 'id' },
    quotations: { keyPath: 'id' },
    user_products: { keyPath: 'id' },
    customers: { keyPath: 'id' },
    sync_queue: { keyPath: 'operation_uuid' },
};

let dbPromise = null;

function openDb() {
    if (!dbPromise) {
        dbPromise = new Promise((resolve, reject) => {
            const req = indexedDB.open(DB_NAME, DB_VERSION);
            req.onupgradeneeded = () => {
                const db = req.result;
                Object.entries(STORES).forEach(([name, cfg]) => {
                    if (!db.objectStoreNames.contains(name)) {
                        const store = db.createObjectStore(name, cfg);
                        if (name === 'user_products') store.createIndex('product_id', 'product_id');
                    }
                });
            };
            req.onsuccess = () => resolve(req.result);
            req.onerror = () => reject(req.error);
        });
    }
    return dbPromise;
}

function tx(store, mode = 'readonly') {
    return openDb().then((db) => new Promise((resolve, reject) => {
        const t = db.transaction(store, mode);
        const done = (req) => (req.onsuccess = () => resolve(req.result), req.onerror = () => reject(req.error));
        t.oncomplete = () => {};
        t.onerror = () => reject(t.error);
        resolve({ t, done, store: t.objectStore(store) });
    }));
}

export const db = {
    async getAll(store) {
        const { store: s } = await tx(store);
        return new Promise((resolve, reject) => {
            const req = s.getAll();
            req.onsuccess = () => resolve(req.result || []);
            req.onerror = () => reject(req.error);
        });
    },
    async get(store, key) {
        const { store: s } = await tx(store);
        return new Promise((resolve, reject) => {
            const req = s.get(key);
            req.onsuccess = () => resolve(req.result);
            req.onerror = () => reject(req.error);
        });
    },
    async put(store, value) {
        const { store: s } = await tx(store, 'readwrite');
        return new Promise((resolve, reject) => {
            const req = s.put(value);
            req.onsuccess = () => resolve(req.result);
            req.onerror = () => reject(req.error);
        });
    },
    async putMany(store, values) {
        if (!values.length) return;
        const { store: s } = await tx(store, 'readwrite');
        return new Promise((resolve, reject) => {
            values.forEach((v) => s.put(v));
            s.transaction.oncomplete = () => resolve();
            s.transaction.onerror = () => reject(s.transaction.error);
        });
    },
    async delete(store, key) {
        const { store: s } = await tx(store, 'readwrite');
        return new Promise((resolve, reject) => {
            const req = s.delete(key);
            req.onsuccess = () => resolve();
            req.onerror = () => reject(req.error);
        });
    },
    async clear(store) {
        const { store: s } = await tx(store, 'readwrite');
        return new Promise((resolve, reject) => {
            const req = s.clear();
            req.onsuccess = () => resolve();
            req.onerror = () => reject(req.error);
        });
    },
};

export async function setMeta(key, value) {
    await db.put('meta', { key, value });
}

export async function getMeta(key) {
    const row = await db.get('meta', key);
    return row ? row.value : undefined;
}
