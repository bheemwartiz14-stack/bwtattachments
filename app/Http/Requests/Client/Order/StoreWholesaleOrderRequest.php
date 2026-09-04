<?php

namespace App\Http\Requests\Client\Order;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreWholesaleOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('quotation.create') ?? false;
    }

       protected function prepareForValidation(): void
    {
        $raw = $this->input('items', '[]');
        $items = is_string($raw) ? json_decode($raw, true) : $raw;
        if (! is_array($items)) $items = [];

        // Hydrate if items are strings (product IDs) -> convert to objects
        if (! empty($items) && is_string(reset($items))) {
            $user = $this->user();
            $decodedIds = array_values(array_filter($items, fn ($v) => is_string($v) && $v !== ''));
            $cart = [];
            if ($user) {
                $cart = session()->get('quotation_cart_'.(string) $user->id, []);
                if (! is_array($cart)) $cart = [];
            }
            $hydrated = [];
            foreach ($decodedIds as $pid) {
                $product = \App\Models\Product::find($pid);
                if (! $product) continue;
                $price = $product->ddp_price ?? 0;
                if ($user) {
                    try {
                        $productWithPrice = app(\App\Services\ProductService::class)->getActiveProductsWithUserPrices($user->id)->firstWhere('id', $pid);
                        if ($productWithPrice) $price = $productWithPrice->productPrices->first()?->final_price ?? $price;
                    } catch (\Throwable $e) {}
                }
                $qty = isset($cart[$pid]) ? (int) $cart[$pid] : 1;
                $hydrated[] = ['product_id' => $pid, 'quantity' => max(1,$qty), 'price' => (float)$price];
            }
            $items = $hydrated;
        }

        $items = array_map(function ($it) {
            if (! is_array($it)) return $it;
            if (! isset($it['quantity']) && isset($it['qty'])) $it['quantity'] = $it['qty'];
            if (! isset($it['price']) && isset($it['unit_price'])) $it['price'] = $it['unit_price'];
            return $it;
        }, $items);

        // Map legacy user_id -> order_from_user_id for backward compat with form
        $fromId = $this->input('order_from_user_id') ?? $this->input('user_id') ?? $this->user()?->id;
        $toId = $this->input('order_to_user_id');
        if (! $toId) {
            try { $toId = \App\Models\User::role('Admin')->first()?->id; } catch (\Throwable $e) {}
        }

        $clean = fn ($v) => str_replace(',', '', (string) ($v ?? ''));
        $this->merge([
            'items' => $items,
            'issue_date' => $this->input('issue_date', now()->format('Y-m-d')),
            'order_from_user_id' => $fromId,
            'order_to_user_id' => $toId,
            'order_reference' => $this->input('order_reference') ?? '',
            'notes' => $this->input('notes') ?? '',
            'order_email_message' => $this->input('order_email_message') ?? null,
            'sub_total' => $clean($this->input('sub_total')),
            'vat_percentage' => $clean($this->input('vat_percentage')),
            'vat_amount' => $clean($this->input('vat_amount', $this->input('tax_amount'))),
            'grand_total' => $clean($this->input('grand_total')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_from_user_id' => ['required', 'exists:users,id'],
            'order_to_user_id' => ['required', 'exists:users,id'],
            'order_number' => ['required', 'string', 'max:255'],
            'order_date' => ['required', 'date', 'after_or_equal:today'],
            'vat_percentage' => ['required', 'string'],
            'sub_total' => ['required', 'string'],
            'vat_amount' => ['required', 'string'],
            'grand_total' => ['required', 'string'],
            'delivery_country' => ['nullable', 'string', 'size:2'],
            'order_reference' => ['nullable', 'string', 'max:255'],
            'show_logo_on_pdf' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'order_email_message' => ['nullable', 'string', 'max:10000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'wholesale_client_logo_temp' => ['nullable', 'string'],
        ];
    }
}
