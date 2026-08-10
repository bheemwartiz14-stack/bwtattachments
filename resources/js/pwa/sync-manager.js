/**
 * Sync manager — orchestrates incremental pull and queued push between the
 * Laravel API and IndexedDB. Runs only when online.
 *
 * Pull: server -> IndexedDB (upsert), applies tombstones for deletions.
 * Push: pending sync_queue -> server, idempotent via operation_uuid.
 */
import * as api from './api';
import { db, getMeta, setMeta } from './db';

const LAST_SYNC_KEY = 'last_synced_at';

function nowIso() {
    return new Date().toISOString();
}

export function isOnline() {
    return typeof navigator.onLine === 'boolean' ? navigator.onLine : true;
}

export async function enqueue(operation) {
    operation.operation_uuid = operation.operation_uuid || (crypto.randomUUID ? crypto.randomUUID() : `${Date.now()}-${Math.random()}`);
    operation.status = 'pending';
    operation.retry_count = 0;
    operation.created_at = nowIso();
    await db.put('sync_queue', operation);
    if (isOnline()) {
        sync().catch(() => {});
    }
    return operation.operation_uuid;
}

export async function getPendingCount() {
    const rows = await db.getAll('sync_queue');
    return rows.filter((r) => r.status === 'pending').length;
}

/**
 * Perform a full sync (pull then push). Safe to call repeatedly.
 */
export async function sync() {
    if (!isOnline() || !api.getToken()) return { pulled: 0, pushed: 0 };

    const lastSync = await getMeta(LAST_SYNC_KEY);

    // ---- PULL ----
    const pulled = await api.pull(lastSync);
    await mergePull(pulled);

    // ---- PUSH (pending queue) ----
    const pushed = await flushQueue();

    if (pulled.server_time) await setMeta(LAST_SYNC_KEY, pulled.server_time);

    return { pulled: summarize(pulled), pushed };
}

function summarize(p) {
    return (
        (p.products?.length || 0) +
        (p.quotations?.length || 0) +
        (p.categories?.length || 0) +
        (p.subcategories?.length || 0) +
        (p.connections?.length || 0) +
        (p.user_products?.length || 0) +
        (p.customers?.length || 0)
    );
}

async function mergePull(pulled) {
    if (pulled.categories?.length) await db.putMany('categories', pulled.categories);
    if (pulled.subcategories?.length) await db.putMany('subcategories', pulled.subcategories);
    if (pulled.connections?.length) await db.putMany('connections', pulled.connections);
    if (pulled.products?.length) await db.putMany('products', pulled.products);
    if (pulled.quotations?.length) await db.putMany('quotations', pulled.quotations);
    if (pulled.user_products?.length) await db.putMany('user_products', pulled.user_products);
    if (pulled.customers?.length) await db.putMany('customers', pulled.customers);

    // Tombstones: remove deleted records locally. map entity -> store name.
    const storeMap = {
        product: 'products',
        quotation: 'quotations',
        category: 'categories',
        subcategory: 'subcategories',
        connection: 'connections',
        user_product: 'user_products',
        customer: 'customers',
        user: 'customers',
    };
    for (const t of pulled.tombstones || []) {
        const store = storeMap[t.entity];
        if (store) await db.delete(store, t.record_id);
    }
}

async function flushQueue() {
    const rows = await db.getAll('sync_queue');
    const pending = rows.filter((r) => r.status === 'pending');
    if (!pending.length) return 0;

    let pushed = 0;
    // Process in small batches.
    for (let i = 0; i < pending.length; i += 25) {
        const batch = pending.slice(i, i + 25);
        const ops = batch.map((r) => ({
            operation_uuid: r.operation_uuid,
            entity: r.entity,
            operation: r.operation,
            payload: r.payload,
        }));

        let res;
        try {
            res = await api.push(ops);
        } catch (e) {
            if (e.status === 401) {
                // auth expired — require re-login
                window.dispatchEvent(new CustomEvent('bwt:auth-expired'));
            }
            // network/server error — keep queue, retry later
            batch.forEach((r) => {
                r.status = 'pending';
                r.last_error = e.message || 'sync error';
                db.put('sync_queue', r);
            });
            break;
        }

        for (const result of res.results) {
            const row = rows.find((r) => r.operation_uuid === result.operation_uuid);
            if (!row) continue;

            if (result.status === 'applied') {
                row.status = 'synced';
                row.server_record_id = result.server_record_id;
                await db.put('sync_queue', row);
                pushed++;
            } else if (result.status === 'conflict') {
                row.status = 'conflict';
                row.server_record_id = result.server_record_id;
                row.last_error = 'Conflict: modified on another device.';
                await db.put('sync_queue', row);
            } else {
                row.status = 'failed';
                row.retry_count = (row.retry_count || 0) + 1;
                row.last_error = JSON.stringify(result.errors || {});
                await db.put('sync_queue', row);
            }
        }
    }

    // Acknowledge synced operations on the server.
    const synced = (await db.getAll('sync_queue')).filter((r) => r.status === 'synced');
    if (synced.length) {
        try {
            await api.ack(synced.map((r) => r.operation_uuid));
            for (const r of synced) await db.delete('sync_queue', r.operation_uuid);
        } catch (e) {
            // ack is idempotent; retry next time
        }
    }

    return pushed;
}

/**
 * Retry failed/conflicted operations (e.g. after a user resolves a conflict).
 */
export async function retry(operationUuid) {
    const row = await db.get('sync_queue', operationUuid);
    if (!row) return;
    row.status = 'pending';
    row.last_error = null;
    await db.put('sync_queue', row);
    await sync();
}
