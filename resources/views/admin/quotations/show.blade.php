<x-layouts.app>
    <x-slot:title>Quotation {{ $quotation->quotation_number }} - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => route('admin.dashboard')], ['label' => 'Quotations', 'url' => route('admin.quotations.index')], ['label' => $quotation->quotation_number]]" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">Quotation {{ $quotation->quotation_number }}</h1>
                <p class="text-sm text-gray-700 mt-1">
                    Created {{ $quotation->created_at->format('M d, Y') }} |
                    Status:
                    @if(($quotation->status ?? 'draft') === 'draft')
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">Draft</span>
                    @elseif(($quotation->status ?? '') === 'declined')
                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/50 dark:text-red-300">Declined</span>
                    @elseif(($quotation->status ?? '') === 'sent')
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">Sent</span>
                    @elseif(($quotation->status ?? '') === 'accepted')
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Accepted</span>
                    @elseif(($quotation->status ?? '') === 'completed')
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Completed</span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-300">{{ ucfirst($quotation->status ?? 'draft') }}</span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.quotations.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                    Back
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Quotation Items</h2>
                    @if($quotation->items->count())
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-100 dark:border-slate-700 bg-rose-50 dark:bg-slate-800/50">
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">#</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Product</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Code</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Qty</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Unit Price</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    @foreach($quotation->items as $index => $item)
                                        <tr class="hover:bg-rose-50 dark:hover:bg-slate-800/50 transition-colors">
                                            <td class="px-4 py-3 text-gray-400">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 font-medium text-black dark:text-gray-100">{{ $item->product->product_description ?? $item->description ?? $item->product_name }}</td>
                                            <td class="px-4 py-3 text-xs font-mono text-emerald-600 dark:text-emerald-400">{{ $item->product->product_code ?? $item->product_code ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-400">{{ $item->quantity }}</td>
                                            <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-400">${{ number_format($item->unit_price, 2) }}</td>
                                            <td class="px-4 py-3 text-right font-semibold text-black dark:text-gray-100">${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-t border-slate-100 dark:border-slate-700 font-semibold">
                                        <td colspan="4"></td>
                                        <td class="px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-300">Subtotal</td>
                                        <td class="px-4 py-3 text-right text-black dark:text-gray-100">${{ number_format($quotation->items->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}</td>
                                    </tr>
                                    @if($quotation->discount > 0)
                                        <tr class="text-sm">
                                            <td colspan="4"></td>
                                            <td class="px-4 py-3 text-right text-gray-700 dark:text-gray-300">Discount ({{ $quotation->discount }}%)</td>
                                            <td class="px-4 py-3 text-right text-red-600">-{{ number_format($quotation->items->sum(fn($i) => $i->quantity * $i->unit_price) * $quotation->discount / 100, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr class="border-t border-slate-100 dark:border-slate-700 text-base font-bold">
                                        <td colspan="4"></td>
                                        <td class="px-4 py-4 text-right text-gray-900 dark:text-gray-100">Total</td>
                                        <td class="px-4 py-4 text-right text-emerald-600 dark:text-emerald-400">${{ number_format($quotation->total_amount ?? $quotation->total ?? $quotation->items->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-sm text-gray-400 dark:text-gray-500 text-center py-6">No items in this quotation.</p>
                    @endif
                </div>

                @if($quotation->notes)
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Notes</h2>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $quotation->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Client Information</h2>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50">
                            <div class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full">
                                {!! Avatar::create($quotation->client->name ?? $quotation->client_name ?? '?')->setDimension(40)->setFontSize(18)->toSvg() !!}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-black dark:text-gray-100">{{ $quotation->client->name ?? $quotation->client_name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-400">{{ $quotation->client->email ?? $quotation->client_email ?? '' }}</p>
                            </div>
                        </div>
                        @if($quotation->client->company ?? $quotation->client_company)
                            <div class="flex items-center gap-3 px-3">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $quotation->client->company ?? $quotation->client_company }}</p>
                            </div>
                        @endif
                        @if($quotation->client->phone ?? $quotation->client_phone)
                            <div class="flex items-center gap-3 px-3">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $quotation->client->phone ?? $quotation->client_phone }}</p>
                            </div>
                        @endif
                        @if($quotation->client->address ?? $quotation->client_address)
                            <div class="flex items-start gap-3 px-3">
                                <svg class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $quotation->client->address ?? $quotation->client_address }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Items</span>
                            <span class="font-medium text-black dark:text-gray-100">{{ $quotation->items->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Subtotal</span>
                            <span class="font-medium text-black dark:text-gray-100">${{ number_format($quotation->items->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}</span>
                        </div>
                        @if($quotation->discount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-700 dark:text-gray-400">Discount</span>
                                <span class="font-medium text-red-600">{{ $quotation->discount }}%</span>
                            </div>
                        @endif
                        <div class="border-t border-slate-100 dark:border-slate-700 pt-3 flex justify-between text-base font-bold">
                            <span class="text-gray-900 dark:text-gray-100">Total</span>
                            <span class="text-emerald-600 dark:text-emerald-400">${{ number_format($quotation->total_amount ?? $quotation->total ?? $quotation->items->sum(fn($i) => $i->quantity * $i->unit_price), 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Quick Actions</h2>
                    <div class="space-y-2">
                        <form action="{{ route('admin.quotations.destroy', $quotation) }}" method="POST" class="block" onsubmit="return confirm('Are you sure you want to delete this quotation?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-sm text-red-600 dark:text-red-400 w-full">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Delete Quotation
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
