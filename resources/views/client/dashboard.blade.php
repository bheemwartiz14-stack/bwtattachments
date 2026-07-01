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

    <div class="space-y-6">
        {{-- Welcome + Notifications row --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- Welcome section --}}
            <div class="lg:col-span-2 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Welcome back, {{ $user->name ?? 'Client' }}</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">Here's what's happening with your account today.</p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Wholesale Client</span>
                </div>
                <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-neutral-500">Company</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-neutral-100">{{ $companyName }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-neutral-500">Client ID</p>
                        <p class="mt-1 text-sm font-semibold font-mono text-slate-900 dark:text-neutral-100">{{ $user->username ?? $user->email }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-neutral-500">Last Login</p>
                        <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-neutral-100">{{ $lastLogin?->format('M d, Y H:i') ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Notifications panel --}}
            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-slate-950 dark:text-neutral-100">Notifications</h2>
                    <span class="inline-flex items-center justify-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">{{ $notifications->count() }}</span>
                </div>
                <div class="mt-4 divide-y divide-slate-100 dark:divide-neutral-800">
                    @forelse($notifications as $notification)
                        <div class="flex items-start gap-3 py-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg
                                {{ $notification['type'] === 'New PDF' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : '' }}
                                {{ $notification['type'] === 'Price Update' ? 'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400' : '' }}">
                                @if($notification['icon'] === 'document-text')
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-slate-900 dark:text-neutral-100">{{ $notification['type'] }}</p>
                                <p class="truncate text-xs text-gray-500 dark:text-neutral-400">{{ $notification['message'] }}</p>
                            </div>
                            <span class="shrink-0 text-xs text-gray-400 dark:text-neutral-500">{{ $notification['time'] }}</span>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-gray-400 dark:text-neutral-500">No notifications yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <h2 class="text-sm font-semibold text-slate-950 dark:text-neutral-100">Quick Actions</h2>
            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-4">
                <a href="{{ route('client.products.index') }}" class="group rounded-xl border border-slate-100 bg-white p-4 text-center transition-all hover:border-emerald-200 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-emerald-800">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 transition-colors group-hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:group-hover:bg-emerald-900/50">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </span>
                    <p class="mt-3 text-sm font-semibold text-slate-900 dark:text-neutral-100">Browse Products</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-neutral-500">View catalog</p>
                </a>
                <a href="{{ route('client.quotations.create') }}" class="group rounded-xl border border-slate-100 bg-white p-4 text-center transition-all hover:border-blue-200 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-blue-800">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition-colors group-hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:group-hover:bg-blue-900/50">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </span>
                    <p class="mt-3 text-sm font-semibold text-slate-900 dark:text-neutral-100">Create Quotation</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-neutral-500">New quote</p>
                </a>
                <a href="{{ route('client.quotations.index', ['status' => 'draft']) }}" class="group rounded-xl border border-slate-100 bg-white p-4 text-center transition-all hover:border-amber-200 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-amber-800">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-amber-50 text-amber-600 transition-colors group-hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:group-hover:bg-amber-900/50">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </span>
                    <p class="mt-3 text-sm font-semibold text-slate-900 dark:text-neutral-100">Saved Drafts</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-neutral-500">{{ $stats['draft_quotations'] }} pending</p>
                </a>
                <a href="{{ route('client.quotations.index') }}" class="group rounded-xl border border-slate-100 bg-white p-4 text-center transition-all hover:border-purple-200 hover:shadow-md dark:border-neutral-800 dark:bg-neutral-950 dark:hover:border-purple-800">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-purple-50 text-purple-600 transition-colors group-hover:bg-purple-100 dark:bg-purple-900/30 dark:text-purple-400 dark:group-hover:bg-purple-900/50">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </span>
                    <p class="mt-3 text-sm font-semibold text-slate-900 dark:text-neutral-100">Download PDFs</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-neutral-500">{{ $stats['downloads'] }} available</p>
                </a>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <x-stat-card label="Products" :value="number_format($stats['total_products'])"
                iconBg="bg-emerald-50 dark:bg-emerald-900/30"
                icon='<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>' />
            <x-stat-card label="Draft Quotes" :value="$stats['draft_quotations']"
                iconBg="bg-amber-50 dark:bg-amber-900/30"
                icon='<svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>' />
            <x-stat-card label="Sent Quotes" :value="$stats['sent_quotations']"
                iconBg="bg-blue-50 dark:bg-blue-900/30"
                icon='<svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>' />
            <x-stat-card label="Downloads" :value="$stats['downloads']"
                iconBg="bg-purple-50 dark:bg-purple-900/30"
                icon='<svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>' />
        </div>

        {{-- Recent Quotations table --}}
        <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-950 dark:text-neutral-100">Recent Quotations</h2>
                <a href="{{ route('client.quotations.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-neutral-400 dark:hover:text-neutral-200">View all</a>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-neutral-800">
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Quotation #</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Items</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-neutral-800">
                        @forelse($recentQuotations ?? [] as $quotation)
                            <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-neutral-900/50">
                                <td class="px-4 py-4">
                                    <a href="{{ route('client.quotations.show', $quotation) }}" class="font-mono text-sm font-medium text-slate-600 hover:text-slate-800 dark:text-neutral-400">
                                        {{ $quotation->quotation_number }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-neutral-400">{{ $quotation->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 dark:text-neutral-400">{{ $quotation->items_count ?? $quotation->items->count() }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-black dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format($quotation->items->sum(fn($i) => $i->price * $i->quantity) * (1 + ($quotation->margin_percentage ?: 0) / 100), 2) }}</td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                        {{ $quotation->status === 'approved' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                                        {{ $quotation->status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                                        {{ $quotation->status === 'draft' ? 'bg-slate-100 text-slate-800 dark:bg-neutral-900 dark:text-neutral-300' : '' }}
                                        {{ $quotation->status === 'sent' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : '' }}
                                        {{ $quotation->status === 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : '' }}">
                                        {{ ucfirst($quotation->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-neutral-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <p class="mt-3 text-sm text-gray-400 dark:text-neutral-500">No quotations yet</p>
                                    <a href="{{ route('client.quotations.create') }}" class="mt-3 inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-slate-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-slate-600">Create your first quotation</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
