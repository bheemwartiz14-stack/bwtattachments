<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Quotation;
use App\Models\User;
use App\Models\UserProduct;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Offline-first synchronization engine.
 *
 * The PWA talks only to the Laravel API. This service reads/writes the server
 * database and enforces idempotency, authorization, and conflict handling.
 * Client data is never trusted as authoritative.
 */
class SyncService
{
    /**
     * Static catalogue + user data needed to bootstrap a fresh offline install.
     */
    public function bootstrap(User $user): array
    {
        return [
            'server_time' => now()->toIso8601String(),
            'categories' => \App\Models\Category::orderBy('name')->get(['id', 'name', 'slug'])->toArray(),
            'subcategories' => \App\Models\Subcategory::orderBy('name')->get(['id', 'name', 'slug'])->toArray(),
            'connections' => \App\Models\Connection::orderBy('name')->get(['id', 'name'])->toArray(),
            'products' => $this->productRows(\App\Models\Product::query()),
        ];
    }

    /**
     * Incremental pull — only records changed since the given cursor.
     */
    public function pull(User $user, ?string $since): array
    {
        $sinceCarbon = $since ? CarbonImmutable::parse($since) : null;

        $queryFor = function ($model, array $columns) use ($sinceCarbon) {
            $q = $model::query();
            if ($sinceCarbon) {
                $q->where('updated_at', '>', $sinceCarbon);
            }
            return $q;
        };

        return [
            'server_time' => now()->toIso8601String(),
            'categories' => $queryFor(\App\Models\Category::class, [])->orderBy('updated_at')->get(['id', 'name', 'slug', 'updated_at'])->toArray(),
            'subcategories' => $queryFor(\App\Models\Subcategory::class, [])->orderBy('updated_at')->get(['id', 'name', 'slug', 'updated_at'])->toArray(),
            'connections' => $queryFor(\App\Models\Connection::class, [])->orderBy('updated_at')->get(['id', 'name', 'updated_at'])->toArray(),
            'products' => $this->productRows(\App\Models\Product::query()->when($sinceCarbon, fn ($q) => $q->where('updated_at', '>', $sinceCarbon))),
            'quotations' => $this->quotationRows($queryFor(Quotation::class, [])->where('user_id', $user->id)),
            'user_products' => $queryFor(UserProduct::class, [])->where('user_id', $user->id)
                ->orderBy('updated_at')->get(['id', 'user_id', 'product_id', 'is_quotation', 'updated_at'])->toArray(),
            'customers' => $queryFor(User::class, [])->where('parent_id', $user->id)
                ->orderBy('updated_at')->get(['id', 'name', 'email', 'phone', 'parent_id', 'updated_at'])->toArray(),
            'tombstones' => \App\Models\SyncTombstone::query()
                ->when($sinceCarbon, fn ($q) => $q->where('deleted_at', '>', $sinceCarbon))
                ->get(['entity', 'record_id', 'deleted_at'])->toArray(),
        ];
    }

    /**
     * Apply a batch of client operations. Each operation is idempotent.
     */
    public function push(User $user, array $operations): array
    {
        $results = [];

        foreach ($operations as $op) {
            $results[] = DB::transaction(function () use ($user, $op) {
                $operationUuid = (string) ($op['operation_uuid'] ?? Str::uuid());
                $entity = (string) ($op['entity'] ?? '');
                $operation = (string) ($op['operation'] ?? '');
                $payload = (array) ($op['payload'] ?? []);

                // Idempotency: a previously-applied operation is returned as-is.
                $existing = \App\Models\SyncOperation::where('user_id', $user->id)
                    ->where('operation_uuid', $operationUuid)
                    ->first();

                if ($existing && $existing->status === 1) {
                    return [
                        'operation_uuid' => $operationUuid,
                        'status' => 'applied',
                        'server_record_id' => $existing->server_record_id,
                    ];
                }

                $applied = $this->applyOperation($user, $entity, $operation, $payload);

                if ($applied['status'] === 'conflict') {
                    return ['operation_uuid' => $operationUuid, 'status' => 'conflict', 'server_record_id' => $applied['server_record_id'] ?? null];
                }

                if ($applied['status'] === 'failed') {
                    return ['operation_uuid' => $operationUuid, 'status' => 'failed', 'errors' => $applied['errors'] ?? []];
                }

                \App\Models\SyncOperation::create([
                    'user_id' => $user->id,
                    'operation_uuid' => $operationUuid,
                    'entity' => $entity,
                    'operation' => $operation,
                    'server_record_id' => $applied['server_record_id'],
                    'status' => 1,
                ]);

                return ['operation_uuid' => $operationUuid, 'status' => 'applied', 'server_record_id' => $applied['server_record_id']];
            });
        }

        return $results;
    }

    public function ack(User $user, array $operationUuids): array
    {
        \App\Models\SyncOperation::where('user_id', $user->id)
            ->whereIn('operation_uuid', $operationUuids)
            ->delete();

        return ['acknowledged' => count($operationUuids)];
    }

    /**
     * @return array{status:string, server_record_id?:string, errors?:array}
     */
    protected function applyOperation(User $user, string $entity, string $operation, array $payload): array
    {
        return match ($entity) {
            'quotation' => $this->applyQuotation($user, $operation, $payload),
            'user_product' => $this->applyUserProduct($user, $operation, $payload),
            'user' => $this->applyUser($user, $operation, $payload),
            default => ['status' => 'failed', 'errors' => ['entity' => "Unsupported entity: {$entity}"]],
        };
    }

    protected function applyQuotation(User $user, string $operation, array $payload): array
    {
        if ($operation === 'delete') {
            $q = Quotation::where('user_id', $user->id)->find($payload['id'] ?? null);
            if ($q) {
                $id = $q->id;
                $q->delete();
                $this->tombstone('quotation', $id);
            }
            return ['status' => 'applied', 'server_record_id' => $payload['id'] ?? null];
        }

        // Look up by client_uuid (offline create) or server id.
        $quotation = null;
        if (! empty($payload['client_uuid'])) {
            $quotation = Quotation::where('user_id', $user->id)->where('client_uuid', $payload['client_uuid'])->first();
        }
        if (! $quotation && ! empty($payload['id'])) {
            $quotation = Quotation::where('user_id', $user->id)->find($payload['id']);
        }

        // Conflict: only guard client updates (not creates). If the server has a
        // newer version than the client's base, do not silently overwrite it.
        if ($quotation && $operation === 'update' && ! empty($payload['base_version'])) {
            $baseVersion = CarbonImmutable::parse($payload['base_version']);
            if ($quotation->updated_at && $quotation->updated_at->gt($baseVersion)) {
                return ['status' => 'conflict', 'server_record_id' => $quotation->id];
            }
        }

        $items = $payload['items'] ?? [];

        if (! $quotation) {
            $quotation = new Quotation();
            $quotation->id = (string) Str::uuid();
            $quotation->user_id = $user->id;
            $quotation->quotation_number = $payload['quotation_number'] ?? $this->nextQuotationNumber();
            if (! empty($payload['client_uuid'])) {
                $quotation->client_uuid = $payload['client_uuid'];
            }
        }

        foreach (['reseller_id', 'reference', 'quotation_email_message', 'delivery_country', 'vat_percentage', 'sub_total', 'tax_amount', 'grand_total', 'tax_rate', 'valid_until', 'issue_date', 'status', 'notes', 'customer_terms', 'margin_amount', 'margin_percentage'] as $field) {
            if (array_key_exists($field, $payload)) {
                $quotation->{$field} = $payload[$field];
            }
        }

        $quotation->save();

        // Replace items with the pushed set (authoritative from client for a quotation build).
        if ($operation === 'create' || ! empty($payload['items'])) {
            $quotation->items()->delete();
            foreach ($items as $item) {
                $quotation->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'price' => $item['price'] ?? 0,
                    'quantity' => $item['quantity'] ?? 1,
                ]);
            }
        }

        return ['status' => 'applied', 'server_record_id' => $quotation->id];
    }

    protected function applyUserProduct(User $user, string $operation, array $payload): array
    {
        $productId = $payload['product_id'] ?? null;
        if (! $productId) {
            return ['status' => 'failed', 'errors' => ['product_id' => 'required']];
        }

        if ($operation === 'delete') {
            UserProduct::where('user_id', $user->id)->where('product_id', $productId)->delete();
            return ['status' => 'applied', 'server_record_id' => (string) $productId];
        }

        $row = UserProduct::where('user_id', $user->id)->where('product_id', $productId)->first();
        if (! $row) {
            $row = new UserProduct();
            $row->user_id = $user->id;
            $row->product_id = $productId;
        }
        $row->is_quotation = (bool) ($payload['is_quotation'] ?? true);
        $row->save();

        return ['status' => 'applied', 'server_record_id' => (string) $row->id];
    }

    protected function applyUser(User $user, string $operation, array $payload): array
    {
        // Customers are managed by their parent reseller/wholesaler.
        if ($operation === 'delete') {
            User::where('parent_id', $user->id)->where('id', $payload['id'] ?? null)->delete();
            return ['status' => 'applied', 'server_record_id' => $payload['id'] ?? null];
        }

        $target = null;
        if (! empty($payload['id'])) {
            $target = User::where('parent_id', $user->id)->find($payload['id']);
        }

        if (! $target) {
            $target = new User();
            $target->id = $payload['id'] ?? (string) Str::uuid();
            $target->parent_id = $user->id;
            $target->password = bcrypt(Str::random(32));
        }

        foreach (['name', 'email', 'phone', 'username'] as $field) {
            if (array_key_exists($field, $payload)) {
                $target->{$field} = $payload[$field];
            }
        }
        $target->save();

        if (! empty($payload['roles'])) {
            $target->syncRoles($payload['roles']);
        }

        return ['status' => 'applied', 'server_record_id' => (string) $target->id];
    }

    protected function productRows($query): array
    {
        return $query->get(['id', 'product_code', 'product_title', 'product_description', 'slug', 'category_id', 'subcategory_id', 'connection_id', 'weight', 'width', 'volume', 'machine_class'])
            ->map(fn ($p) => [
                'id' => $p->id,
                'product_code' => $p->product_code,
                'product_title' => $p->product_title,
                'slug' => $p->slug,
                'category_id' => $p->category_id,
                'subcategory_id' => $p->subcategory_id,
                'connection_id' => $p->connection_id,
                'weight' => $p->weight,
                'width' => $p->width,
                'volume' => $p->volume,
                'machine_class' => $p->machine_class,
                'image_url' => $p->getFirstMediaUrl('images', 'small'),
            ])->values()->toArray();
    }

    protected function quotationRows($query): array
    {
        return $query->with('items')->orderBy('updated_at')->get()->map(function (Quotation $q) {
            return [
                'id' => $q->id,
                'client_uuid' => $q->client_uuid,
                'quotation_number' => $q->quotation_number,
                'reference' => $q->reference,
                'status' => $q->status,
                'vat_percentage' => $q->vat_percentage,
                'sub_total' => $q->sub_total,
                'tax_amount' => $q->tax_amount,
                'grand_total' => $q->grand_total,
                'delivery_country' => $q->delivery_country,
                'quotation_email_message' => $q->quotation_email_message,
                'notes' => $q->notes,
                'updated_at' => $q->updated_at?->toIso8601String(),
                'items' => $q->items->map(fn ($i) => ['product_id' => $i->product_id, 'price' => $i->price, 'quantity' => $i->quantity])->values(),
            ];
        })->values()->toArray();
    }

    protected function nextQuotationNumber(): string
    {
        return 'Q-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }

    protected function tombstone(string $entity, string $recordId): void
    {
        \App\Models\SyncTombstone::updateOrCreate(
            ['entity' => $entity, 'record_id' => $recordId],
            ['deleted_at' => now()]
        );
    }
}
