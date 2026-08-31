<div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
    <div class="p-6">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-neutral-800">
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 w-10">#</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Product</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 w-16">Qty</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 w-14">Unit</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400 w-24">Unit Price</th>
                        <th class="px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400 w-24">Line Total</th>
                        <th class="px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400 w-10"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-neutral-800">
                    @forelse($items as $index => $item)
                        @php $lineTotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 1); @endphp
                        <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-neutral-900/50">
                            <td class="px-3 py-3 text-xs text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-3 py-3">
                                <p class="text-sm font-medium text-slate-900 dark:text-neutral-100">{{ $item['product_title'] ?? '' }}</p>
                                @if($item['product_code'] ?? false)
                                    <p class="text-xs text-slate-400 dark:text-neutral-500 font-mono">{{ $item['product_code'] }}</p>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                @php $qty = (int) ($item['quantity'] ?? 1); $cartId = $item['id'] ?? $item['product_id']; @endphp
                                <div class="flex items-center gap-1">
                                    <button
                                        type="button"
                                        wire:click="decrement('{{ $cartId }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="decrement('{{ $cartId }}'), increment('{{ $cartId }}')"
                                        @disabled($qty <= 1)
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                                        aria-label="Decrease quantity"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                                    </button>
                                    <span class="inline-flex h-7 min-w-[2rem] items-center justify-center rounded-lg border border-slate-200 bg-slate-50 px-2 text-sm font-semibold text-slate-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                        <span wire:loading.remove wire:target="decrement('{{ $cartId }}'), increment('{{ $cartId }}')">{{ $qty }}</span>
                                        <svg wire:loading wire:target="decrement('{{ $cartId }}'), increment('{{ $cartId }}')" class="h-4 w-4 animate-spin text-slate-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    </span>
                                    <button
                                        type="button"
                                        wire:click="increment('{{ $cartId }}')"
                                        wire:loading.attr="disabled"
                                        wire:target="increment('{{ $cartId }}'), decrement('{{ $cartId }}')"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800"
                                        aria-label="Increase quantity"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-sm text-slate-500 dark:text-neutral-400">pcs</td>
                            <td class="px-3 py-3">
                                <input type="number" value="{{ number_format($item['price'] ?? 0, 2, '.', '') }}" step="0.01" min="0"
                                    class="w-24 rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-right text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100"
                                    wire:change="updatePrice({{ $index }}, $event.target.value)" readonly>
                            </td>
                            <td class="px-3 py-3 text-right text-sm font-semibold text-slate-900 dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format($lineTotal, 2) }}</td>
                            <td class="px-3 py-3 text-right">
                                <button
                                    type="button"
                                    data-quote="{{ $item['product_id'] }}"
                                    data-added="true"
                                    onclick="toggleQuoteItem(this)"
                                    class="rounded-lg p-1.5 text-slate-300 transition-colors hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20"
                                    aria-label="Remove from quotation"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                <p class="mt-3 text-sm font-medium text-slate-500 dark:text-neutral-400">No items added yet</p>
                                <p class="mt-1 text-xs text-slate-400 dark:text-neutral-500">Click "Add Item" to select products</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($items) > 0)
            <div class="mt-6 overflow-hidden rounded-xl border border-slate-100 bg-slate-50/70 dark:border-neutral-800 dark:bg-neutral-900/50">
                <div class="p-4 sm:p-5">
                    <div class="ml-auto max-w-sm space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-slate-500 dark:text-neutral-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Subtotal
                            </span>
                            <span class="font-semibold text-slate-900 dark:text-white">{{ config('app.currency_symbol') }}{{ number_format($this->subtotal, 2) }}</span>
                            <input type="hidden" name="sub_total" value="{{ number_format($this->subtotal, 2) }}">
                            <input type="hidden" name="margin_amount" value="0">
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="flex items-center gap-2 text-slate-500 dark:text-neutral-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> VAT ({{ $this->taxRate }}%)
                            </span>
                            <span class="font-semibold text-amber-600 dark:text-amber-400">{{ config('app.currency_symbol') }}{{ number_format($this->taxAmount, 2) }}</span>
                            <input type="hidden" name="vat_percentage" value="{{ $this->taxRate }}">
                            <input type="hidden" name="tax_amount" value="{{ number_format($this->taxAmount, 2) }}">
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-200 pt-3 text-base font-bold dark:border-neutral-700">
                            <span class="text-slate-900 dark:text-white">Grand Total</span>
                            <span class="text-slate-900 dark:text-white">{{ config('app.currency_symbol') }}{{ number_format($this->grandTotal, 2) }}</span>
                            <input type="hidden" name="grand_total" value="{{ number_format($this->grandTotal, 2) }}">
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
    @script
        $wire.dispatch('itemsUpdated', { items: @js($items) });
    @endscript
</div>
