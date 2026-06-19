<x-layouts.app>
    <x-slot:title>Quotation Builder - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Client Portal', 'url' => '#'], ['label' => 'Quotation Builder']]" />

    <div x-data="quotation()" class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">Quotation Builder</h1>
                <p class="text-sm text-gray-700 mt-1">Review and finalize your quotation</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Email
                </button>
                <button class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Download PDF
                </button>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-mono font-semibold text-black dark:text-gray-100">Quotation #Q-2024-0043</h2>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Prepared for ABC Construction &middot; {{ date('F d, Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">Draft</span>
                </div>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                <template x-for="(item, index) in items" :key="item.id">
                    <div class="px-6 py-4 hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 shrink-0">
                                <svg class="w-7 h-7 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-black dark:text-gray-100" x-text="item.name"></p>
                                        <p class="text-xs font-mono text-gray-400 dark:text-gray-500 mt-0.5" x-text="item.code"></p>
                                    </div>
                                    <button @@click="removeItem(item.id)" class="shrink-0 text-gray-400 dark:text-gray-500 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                                <div class="mt-2 grid grid-cols-3 gap-4 text-xs text-gray-700 dark:text-gray-400">
                                    <span>Weight: <span class="font-medium text-black dark:text-gray-100" x-text="item.weight"></span></span>
                                    <span>Width: <span class="font-medium text-black dark:text-gray-100" x-text="item.width"></span></span>
                                    <span>
                                        Qty:
                                        <input type="number" x-model="item.qty" min="1" class="inline-block w-14 rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                                    </span>
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Unit Price:</span>
                                        <span class="text-sm font-semibold text-black dark:text-gray-100" x-html="'$' + item.price.toLocaleString()"></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400 dark:text-gray-500">Final:</span>
                                        <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400" x-html="'$' + calculateFinalPrice(item).toLocaleString()"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-rose-50 dark:bg-slate-800/50">
                <div class="max-w-sm ml-auto space-y-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Margin Percentage</label>
                        <div class="flex items-center gap-3">
                            <input type="range" x-model="margin" min="0" max="50" class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full appearance-none cursor-pointer accent-emerald-600">
                            <div class="flex items-center gap-1">
                                <input type="number" x-model="margin" min="0" max="100" class="block w-16 rounded-lg border border-slate-200 bg-white px-2 py-1 text-sm text-black text-center placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                                <span class="text-sm text-gray-400 dark:text-gray-500">%</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm pt-2">
                        <span class="text-gray-700 dark:text-gray-400">Subtotal (DDP)</span>
                        <span class="font-medium text-black dark:text-gray-100" x-html="'$' + totalWithoutMargin().toLocaleString()"></span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Margin ( <span x-text="margin"></span>% )</span>
                        <span class="font-medium text-emerald-600 dark:text-emerald-400" x-html="'+$' + (total() - totalWithoutMargin()).toLocaleString()"></span>
                    </div>
                    <div class="flex items-center justify-between text-lg font-bold pt-2 border-t border-slate-100 dark:border-slate-700">
                        <span class="text-black dark:text-gray-100">Grand Total</span>
                        <span class="text-black dark:text-gray-100" x-html="'$' + total().toLocaleString()"></span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    <span x-text="items.length"></span> item(s) &middot; Generated on {{ date('Y-m-d H:i') }}
                </p>
                <div class="flex items-center gap-2">
                    <button @@click="items = []" x-show="items.length > 0" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">Clear All</button>
                    <button class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Save Quotation
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>