<x-layouts.app>
    <x-slot:title>Client Dashboard - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Client Portal']]" />

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

    <div x-data="quotation()" class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">Welcome back, {{ auth()->user()->name ?? 'Client' }}</h1>
                <p class="text-sm text-gray-700 mt-1">Browse products and build quotations</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Wholesale Client</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card label="Total Quotations" :value="$stats['total_quotations'] ?? 0" trend="up" trendText="All time"
                iconBg="bg-emerald-50 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>' />
            <x-stat-card label="Pending Quotations" :value="$stats['pending_quotations'] ?? 0" trend="up" trendText="Awaiting response"
                iconBg="bg-amber-50 dark:bg-amber-900/30"
                icon='<svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
            <x-stat-card label="Products Viewed" :value="$stats['total_products_viewed'] ?? 0" trend="up" trendText="This week"
                iconBg="bg-emerald-50 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>' />
            <x-stat-card label="Saved Products" value="0" trend="up" trendText="In quote list"
                iconBg="bg-violet-50 dark:bg-violet-900/30"
                icon='<svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>' />
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Recent Quotations</h2>
                    <a href="{{ route('client.quotations.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">View all</a>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 bg-white dark:border-slate-700 dark:bg-slate-800/50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Quotation #</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @forelse($recentQuotations ?? [] as $quotation)
                                    <tr class="transition-colors hover:bg-rose-50 dark:hover:bg-slate-800/50">
                                        <td class="px-6 py-4">
                                            <a href="{{ route('client.quotations.show', $quotation) }}" class="font-mono text-sm font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">
                                                {{ $quotation->quotation_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">{{ $quotation->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">{{ $quotation->items_count ?? $quotation->items->count() }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-black dark:text-gray-100">${{ number_format($quotation->total, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                {{ $quotation->status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                                                {{ $quotation->status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                                                {{ $quotation->status === 'draft' ? 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300' : '' }}
                                                {{ $quotation->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : '' }}">
                                                {{ ucfirst($quotation->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                            <p class="mt-3 text-sm text-gray-400 dark:text-gray-500">No quotations yet</p>
                                            <a href="{{ route('client.quotations.create') }}" class="mt-3 inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">Create your first quotation</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ route('client.products.index') }}" class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-colors hover:bg-rose-50 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/30">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-black dark:text-gray-100">Browse Products</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">View our full product catalog</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    <a href="{{ route('client.quotations.create') }}" class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-colors hover:bg-rose-50 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-900/30">
                            <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-black dark:text-gray-100">Create Quotation</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Build a new quotation</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                    <a href="{{ route('client.profile.edit') }}" class="flex items-center gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition-colors hover:bg-rose-50 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800">
                        <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-50 dark:bg-violet-900/30">
                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-black dark:text-gray-100">View Profile</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Manage your account settings</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
