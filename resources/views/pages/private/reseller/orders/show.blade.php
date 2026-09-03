<x-layouts.app>
    @php $displayNumber = $order->order_number ?? $order->quotation_number ?? ''; @endphp
    <x-slot:title>{{ $displayNumber }} - BWT</x-slot:title>
    <x-breadcrumb :items="[
        ['label' => 'Reseller Portal', 'url' => route('reseller.dashboard')],
        ['label' => 'Orders', 'url' => route('reseller.orders.index')],
        ['label' => $displayNumber],
    ]" />

    @if (session('success'))
        <div
            class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-6">
        <x-ui.hero title="Order {{ $displayNumber }}" icon="heroicon-o-document-text"
            subtitle="View order details">
            <x-slot:actions>
                <form action="{{ route('reseller.orders.download', $order) }}" method="GET" class="inline">
                    <x-ui.button type="submit" variant="secondary" label="Download PDF">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </x-ui.button>
                </form>
                <a href="{{ route('reseller.orders.index') }}" wire:navigate
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition-all hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                    Back to Orders
                </a>
            </x-slot:actions>
        </x-ui.hero>
    </div>

    <div class="space-y-6">

        <div
            class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-neutral-800 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-mono font-semibold text-black dark:text-neutral-100">
                        {{ $displayNumber }}</h2>
                    <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Created
                        {{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @php
                        $statusClasses = [
                            'draft' => 'bg-slate-100 text-slate-800 dark:bg-neutral-900 dark:text-neutral-300',
                            'sent' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300',
                            'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300',
                            'approved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300',
                            'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
                            'submitted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
                        ];
                        $statusValue = $order->status instanceof \BackedEnum ? $order->status->value : (string) $order->status;
                        $class = $statusClasses[$statusValue] ?? 'bg-slate-100 text-slate-800 dark:bg-neutral-900 dark:text-neutral-300';
                    @endphp
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $class }}">
                        {{ ucfirst($statusValue) }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm table-fixed">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/70 dark:border-neutral-800 dark:bg-neutral-900/50">
                            <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500" style="width:15%">Product code</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500" style="width:30%">Product name</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500" style="width:18%">Unit price</th>
                            <th class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-500" style="width:17%">Qty</th>
                            <th class="px-3 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500" style="width:20%">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-neutral-800">
                        @forelse($order->items as $item)
                            @php $lineTotal = $item->price * $item->quantity; @endphp
                            <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-neutral-900/50">
                                <td class="px-3 py-3 text-left align-middle"><span class="text-xs font-mono font-semibold text-slate-700 dark:text-neutral-300">{{ $item->product?->product_code ?? '—' }}</span></td>
                                <td class="px-3 py-3 text-left align-middle"><p class="text-sm font-semibold text-slate-900 dark:text-neutral-100 leading-tight">{{ $item->product?->product_title ?? 'Product' }}</p></td>
                                <td class="px-3 py-3 text-center text-sm font-medium text-slate-900 dark:text-neutral-100 align-middle">{{ config('app.currency_symbol') }}{{ number_format($item->price, 2) }}</td>
                                <td class="px-3 py-3 text-center align-middle"><span class="inline-flex h-7 min-w-[2.2rem] items-center justify-center rounded-lg border border-slate-200 bg-slate-50 px-2 text-sm font-bold text-slate-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">{{ $item->quantity }}</span></td>
                                <td class="px-3 py-3 text-right text-sm font-bold text-slate-900 dark:text-neutral-100 align-middle">{{ config('app.currency_symbol') }}{{ number_format($lineTotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center"><svg class="w-12 h-12 mx-auto text-gray-400 dark:text-neutral-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg><p class="mt-3 text-sm text-gray-400 dark:text-neutral-500">No items in this order</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @php
                $rawVatPerc = $order->getAttributes()['vat_percentage'] ?? 0;
                $taxRate = is_string($rawVatPerc) ? str_replace(',', '', $rawVatPerc) : $rawVatPerc;
                $rawSub = $order->getAttributes()['sub_total'] ?? 0;
                $rawVatAmt = $order->getAttributes()['vat_amount'] ?? $order->getAttributes()['vat_amount'] ?? 0;
                $rawGrand = $order->getAttributes()['grand_total'] ?? 0;
            @endphp
            <div class="px-6 py-4 border-t border-slate-100 dark:border-neutral-800 bg-rose-50 dark:bg-neutral-900/50">
                <div class="max-w-sm ml-auto space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700 dark:text-neutral-400">Subtotal</span>
                        <span class="font-medium text-black dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format((float)str_replace(',', '', (string)$rawSub), 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700 dark:text-neutral-400">VAT ({{ $taxRate }}%)</span>
                        <span class="font-medium text-amber-600 dark:text-amber-400">{{ config('app.currency_symbol') }}{{ number_format((float)str_replace(',', '', (string)$rawVatAmt), 2) }}</span>
                    </div>

                    <div
                        class="flex items-center justify-between text-lg font-bold pt-2 border-t border-slate-100 dark:border-neutral-800">
                        <span class="text-black dark:text-neutral-100">Total incl. VAT</span>
                        <span class="text-black dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format((float)str_replace(',', '', (string)$rawGrand), 2) }}</span>
                    </div>
                </div>
            </div>

            @if ($order->notes)
                <div class="px-6 py-4 border-t border-slate-100 dark:border-neutral-800">
                    <p class="text-xs font-medium text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-1">
                        Notes</p>
                    <div class="prose prose-sm lg:prose-base max-w-none dark:prose-invert">
                        {!! $order->notes !!}
                    </div>
            @endif


            <div class="px-6 py-4 border-t border-slate-100 dark:border-neutral-800 flex items-center justify-between">
                <p class="text-xs text-gray-400 dark:text-neutral-500">
                    {{ $order->items->count() }} item(s) &middot; Generated on
                    {{ $order->created_at->format('M d, Y') }}
                </p>
                <form action="{{ route('reseller.orders.download', $order) }}" method="GET" class="inline">
                    <x-ui.button type="submit" variant="secondary" label="Download PDF">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </x-ui.button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
