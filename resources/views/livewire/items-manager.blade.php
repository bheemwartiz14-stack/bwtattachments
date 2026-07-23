<div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-rose-500 to-rose-600 shadow-sm">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 11.625l2.25-2.25M12 11.625l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Items</h2>
                <p class="text-xs text-slate-500 dark:text-neutral-400">Add products to your quotation</p>
            </div>
        </div>
        <button type="button" wire:click="openModal"
            class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:bg-emerald-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:hover:bg-emerald-500">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Add Item
        </button>
    </div>
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
                                <input type="number" value="{{ $item['quantity'] ?? 1 }}" min="1"
                                    class="w-16 rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-center text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100"
                                    wire:change="updateQty({{ $index }}, $event.target.value)">
                            </td>
                            <td class="px-3 py-3 text-sm text-slate-500 dark:text-neutral-400">pcs</td>
                            <td class="px-3 py-3">
                                <input type="number" value="{{ number_format($item['price'] ?? 0, 2, '.', '') }}" step="0.01" min="0"
                                    class="w-24 rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-right text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100"
                                    wire:change="updatePrice({{ $index }}, $event.target.value)" readonly>
                            </td>
                            <td class="px-3 py-3 text-right text-sm font-semibold text-slate-900 dark:text-neutral-100">&euro;{{ number_format($lineTotal, 2) }}</td>
                            <td class="px-3 py-3 text-right">
                                <button type="button" wire:click="removeItem({{ $index }})" class="rounded-lg p-1.5 text-slate-300 transition-colors hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20">
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
            <div class="mt-4 border-t border-slate-100 pt-4 dark:border-neutral-800">
                <div class="ml-auto max-w-xs space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 dark:text-neutral-400">Subtotal</span>
                        <span class="font-medium text-slate-900 dark:text-white">&euro;{{ number_format($this->subtotal, 2) }}</span>
                        <input type="hidden" name="sub_total" value="{{ number_format($this->subtotal, 2) }}">
                        <input type="hidden" name="margin_amount" value="0">
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500 dark:text-neutral-400">VAT ({{ $this->taxRate }}%)</span>
                        <input type="hidden" name="vat_percentage" value="{{ $this->taxRate }}">
                        <span class="font-medium text-amber-600 dark:text-amber-400">&euro;{{ number_format($this->taxAmount, 2) }}</span>
                        <input type="hidden" name="tax_amount" value="{{ number_format($this->taxAmount, 2) }}">
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-200 pt-2 text-base font-semibold dark:border-neutral-700">
                        <span class="text-slate-900 dark:text-white">Grand Total</span>
                        <span class="text-slate-900 dark:text-white">&euro;{{ number_format($this->grandTotal, 2) }}</span>
                        <input type="hidden" name="grand_total" value="{{ number_format($this->grandTotal, 2) }}">
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Product Search Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" wire:click="closeModal"></div>
            <div class="relative w-full sm:max-w-2xl rounded-2xl border border-slate-200 bg-white shadow-2xl dark:border-neutral-700 dark:bg-neutral-900">
                <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-4 dark:border-neutral-800">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-rose-500 to-rose-600 shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 11.625l2.25-2.25M12 11.625l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">Select Products</h3>
                        <p class="text-xs text-slate-500 dark:text-neutral-400">Choose products to add to your quotation</p>
                    </div>
                    <button type="button" wire:click="closeModal" class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 dark:hover:bg-neutral-800">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="px-6 py-3 border-b border-slate-100 dark:border-neutral-800">
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 dark:text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <input type="text" wire:model.live.debounce.200ms="search" placeholder="Search by product name, code, or description..."
                            class="block w-full rounded-xl border border-slate-200 bg-slate-50 pl-10 pr-4 py-3 text-sm text-slate-900 placeholder-slate-400 transition-all focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-white dark:placeholder-neutral-500 dark:focus:bg-neutral-800">
                    </div>
                </div>
                <div class="max-h-96 overflow-y-auto p-1.5">
                    @forelse($products as $product)
                        @php
                            $price = $product->productPrices->first()?->final_price ?? $product->ddp_price ?? 0;
                            $added = collect($items)->contains('product_id', $product->id);
                        @endphp
                        @if($added)
                            <button type="button" disabled
                                class="flex w-full items-center gap-4 rounded-xl px-4 py-3.5 text-left text-sm opacity-50 cursor-default mb-0.5">
                        @else
                            <button type="button" wire:click="addItem('{{ $product->id }}')"
                                class="flex w-full items-center gap-4 rounded-xl px-4 py-3.5 text-left text-sm transition-all hover:bg-slate-50 hover:shadow-sm mb-0.5 dark:hover:bg-neutral-800">
                        @endif
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-slate-100 to-slate-200 text-sm font-bold text-slate-600 shadow-sm dark:from-neutral-800 dark:to-neutral-700 dark:text-neutral-300">
                                {{ strtoupper(substr($product->product_title, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900 dark:text-white">{{ $product->product_title }}</p>
                                <p class="mt-0.5 text-xs text-slate-500 dark:text-neutral-400">{{ $product->product_code ?? '' }}{{ $product->category ? ' · ' . $product->category->name : '' }}</p>
                            </div>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">&euro;{{ number_format($price, 2) }}</span>
                            <span class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-medium shadow-sm {{ $added ? 'bg-slate-100 text-slate-500 dark:bg-neutral-800 dark:text-neutral-400' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">
                                @if($added)
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                @endif
                                {{ $added ? 'Added' : 'Add' }}
                            </span>
                        </button>
                    @empty
                        <div class="px-4 py-12 text-center">
                            <svg class="mx-auto h-10 w-10 text-slate-300 dark:text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <p class="mt-3 text-sm font-medium text-slate-500 dark:text-neutral-400">No products match your search</p>
                            <p class="mt-0.5 text-xs text-slate-400 dark:text-neutral-500">Try a different product name or code</p>
                        </div>
                    @endforelse
                </div>
                <div class="flex items-center justify-end border-t border-slate-100 px-6 py-4 dark:border-neutral-800">
                    <button type="button" wire:click="closeModal" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-neutral-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
