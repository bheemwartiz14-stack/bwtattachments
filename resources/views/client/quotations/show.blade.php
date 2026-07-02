<x-layouts.app>
    <x-slot:title>{{ $quotation->quotation_number }} - BWT</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Client Portal', 'url' => route('client.dashboard')], ['label' => 'My Quotations', 'url' => route('client.quotations.index')], ['label' => $quotation->quotation_number]]" />

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Quotation {{ $quotation->quotation_number }}</h1>
                <p class="text-sm text-gray-700 mt-1">View quotation details</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('client.quotations.download', $quotation) }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:hover:bg-neutral-800">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Download PDF
                </a>
                <a href="{{ route('client.quotations.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:hover:bg-neutral-800">
                    Back to Quotations
                </a>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-neutral-800 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-mono font-semibold text-black dark:text-neutral-100">{{ $quotation->quotation_number }}</h2>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Created {{ $quotation->created_at->format('F d, Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @php
                        $statusClasses = [
                            'draft' => 'bg-slate-100 text-slate-800 dark:bg-neutral-900 dark:text-neutral-300',
                            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300',
                            'approved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300',
                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                            'submitted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
                        ];
                        $class = $statusClasses[$quotation->status] ?? 'bg-slate-100 text-slate-800 dark:bg-neutral-900 dark:text-neutral-300';
                    @endphp
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $class }}">
                        {{ ucfirst($quotation->status) }}
                    </span>
                </div>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-neutral-800">
                @forelse($quotation->items as $item)
                    @php $lineTotal = $item->price * $item->quantity; @endphp
                    <div class="px-6 py-4 hover:bg-rose-50 dark:hover:bg-neutral-900/50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="flex h-14 w-14 items-center justify-center rounded-lg bg-slate-100 dark:bg-neutral-900 shrink-0">
                                <svg class="w-7 h-7 text-gray-400 dark:text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-black dark:text-neutral-100">{{ $item->product?->product_description ?? $item->product?->product_title ?? 'Product' }}</p>
                                        <p class="text-xs font-mono text-gray-400 dark:text-neutral-500 mt-0.5">{{ $item->product?->product_code }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 grid grid-cols-3 gap-4 text-xs text-gray-700 dark:text-neutral-400">
                                    <span>Quantity: <span class="font-medium text-black dark:text-neutral-100">{{ $item->quantity }}</span></span>
                                    <span>Unit Price: <span class="font-medium text-black dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format($item->price, 2) }}</span></span>
                                    <span>Total: <span class="font-medium text-emerald-600 dark:text-emerald-400">{{ config('app.currency_symbol') }}{{ number_format($lineTotal, 2) }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-neutral-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                        <p class="mt-3 text-sm text-gray-400 dark:text-neutral-500">No items in this quotation</p>
                    </div>
                @endforelse
            </div>

            @php
                $subtotal = $quotation->items->sum(fn($i) => $i->price * $i->quantity);
                $marginAmount = $subtotal * ($quotation->margin_percentage / 100);
                $grandTotal = $subtotal + $marginAmount;
            @endphp
            <div class="px-6 py-4 border-t border-slate-100 dark:border-neutral-800 bg-rose-50 dark:bg-neutral-900/50">
                <div class="max-w-sm ml-auto space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700 dark:text-neutral-400">Subtotal</span>
                        <span class="font-medium text-black dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($quotation->margin_percentage > 0)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-700 dark:text-neutral-400">Margin ({{ $quotation->margin_percentage }}%)</span>
                            <span class="font-medium text-emerald-600 dark:text-emerald-400">+{{ config('app.currency_symbol') }}{{ number_format($marginAmount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between text-lg font-bold pt-2 border-t border-slate-100 dark:border-neutral-800">
                        <span class="text-black dark:text-neutral-100">Grand Total</span>
                        <span class="text-black dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
            </div>

            @if($quotation->notes)
                <div class="px-6 py-4 border-t border-slate-100 dark:border-neutral-800">
                    <p class="text-xs font-medium text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-1">Notes</p>
                    <p class="text-sm text-gray-700 dark:text-neutral-400">{{ $quotation->notes }}</p>
                </div>
            @endif

            <div class="px-6 py-4 border-t border-slate-100 dark:border-neutral-800 flex items-center justify-between">
                <p class="text-xs text-gray-400 dark:text-neutral-500">
                    {{ $quotation->items->count() }} item(s) &middot; Generated on {{ $quotation->created_at->format('Y-m-d H:i') }}
                </p>
                <a href="{{ route('client.quotations.download', $quotation) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Download PDF
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
