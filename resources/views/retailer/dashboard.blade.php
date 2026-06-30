<x-layouts.app>
    <x-slot:title>Retailer Dashboard - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Retailer Portal']]" />

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
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Welcome back, {{ auth()->user()->name ?? 'Retailer' }}</h1>
                <p class="text-sm text-gray-700 mt-1">Browse products and manage quotations</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Retailer</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Total Products" :value="$stats['total_products'] ?? 0" trend="up" trendText="In catalog"
                iconBg="bg-slate-100 dark:bg-neutral-900"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>' />
            <x-stat-card label="Categories" :value="$stats['total_categories'] ?? 0" trend="up" trendText="Available"
                iconBg="bg-slate-100 dark:bg-neutral-900"
                icon='<svg class="w-6 h-6 text-slate-700 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>' />
            <x-stat-card label="Total Quotations" :value="$stats['total_quotations'] ?? 0" trend="up" trendText="All time"
                iconBg="bg-slate-100 dark:bg-neutral-900"
                icon='<svg class="w-6 h-6 text-slate-700 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>' />
            <x-stat-card label="Pending Quotations" :value="$stats['pending_quotations'] ?? 0" trend="up" trendText="Awaiting response"
                iconBg="bg-slate-100 dark:bg-neutral-900"
                icon='<svg class="w-6 h-6 text-slate-600 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-neutral-100">Recent Quotations</h2>
                    <a href="{{ route('retailer.quotations.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-800">View all</a>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 bg-white dark:border-neutral-800 dark:bg-neutral-900/50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Quotation #</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-neutral-800">
                                @forelse($recentQuotations ?? [] as $quotation)
                                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-neutral-900/50">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('retailer.quotations.show', $quotation) }}" class="font-mono text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-neutral-400">
                                                {{ $quotation->quotation_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-neutral-400">{{ $quotation->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-neutral-400">{{ $quotation->items_count ?? $quotation->items->count() }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-black dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format($quotation->items->sum(fn($i) => $i->price * $i->quantity) * (1 + ($quotation->margin_percentage ?: 0) / 100), 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                {{ $quotation->status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                                                {{ $quotation->status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                                                {{ $quotation->status === 'draft' ? 'bg-slate-100 text-slate-800 dark:bg-neutral-900 dark:text-neutral-300' : '' }}
                                                {{ $quotation->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : '' }}">
                                                {{ ucfirst($quotation->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-neutral-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <p class="mt-3 text-sm text-gray-400 dark:text-neutral-500">No quotations yet</p>
                                            <a href="{{ route('retailer.quotations.create') }}" class="mt-3 inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-slate-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700">Create your first quotation</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-neutral-100">Recent Products</h2>
                    <a href="{{ route('retailer.products.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-800">View all</a>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 bg-white dark:border-neutral-800 dark:bg-neutral-900/50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-neutral-800">
                                @forelse($recentProducts ?? [] as $product)
                                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-neutral-900/50">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('retailer.products.show', $product) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-neutral-400">
                                                {{ $product->product_title }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-neutral-400">{{ $product->product_code }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-neutral-400">{{ $product->category->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                {{ $product->status ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                                {{ $product->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-neutral-500">No products available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-base font-semibold text-slate-950 dark:text-neutral-100">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('retailer.products.index') }}" class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-colors hover:bg-slate-50 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:bg-neutral-900">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/30">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-black dark:text-neutral-100">Browse Products</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500">View product catalog</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    <a href="{{ route('retailer.quotations.create') }}" class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-colors hover:bg-slate-50 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:bg-neutral-900">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 dark:bg-neutral-900">
                            <svg class="w-6 h-6 text-slate-600 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-black dark:text-neutral-100">Create Quotation</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500">Build a new quotation</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    <a href="{{ route('retailer.profile.edit') }}" class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-colors hover:bg-slate-50 dark:border-neutral-800 dark:bg-neutral-950 dark:hover:bg-neutral-900">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 dark:bg-neutral-900">
                            <svg class="w-6 h-6 text-slate-700 dark:text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-black dark:text-neutral-100">View Profile</p>
                            <p class="text-xs text-gray-400 dark:text-neutral-500">Manage account settings</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
