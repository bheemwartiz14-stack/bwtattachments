<x-layouts.app>
    <x-slot:title>Quotations - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => route('admin.dashboard')], ['label' => 'Quotations']]" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">Quotations</h1>
                <p class="text-sm text-gray-700 mt-1">Manage all client quotations</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export All
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-800 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.quotations.index') }}" class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by number or client..." class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Status</label>
                    <select name="status" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                        <option value="">All Status</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="sent" @selected(request('status') === 'sent')>Sent</option>
                        <option value="accepted" @selected(request('status') === 'accepted')>Accepted</option>
                        <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                        <option value="declined" @selected(request('status') === 'declined')>Declined</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 mt-3">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    Filter
                </button>
                <a href="{{ route('admin.quotations.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                    Clear
                </a>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700 bg-rose-50 dark:bg-slate-800/50">
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Quotation #</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($quotations ?? [] as $quotation)
                            <tr class="hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 font-mono text-sm font-medium text-emerald-600 dark:text-emerald-400">{{ $quotation->quotation_number }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ $quotation->client->name ?? $quotation->client_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ $quotation->items_count ?? $quotation->items->count() ?? 0 }}</td>
                                <td class="px-6 py-4 font-semibold text-black dark:text-gray-100">${{ number_format($quotation->total_amount ?? $quotation->total ?? 0, 2) }}</td>
                                <td class="px-6 py-4">
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
                                </td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ $quotation->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.quotations.show', $quotation) }}" title="View" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-gray-400 dark:hover:bg-slate-800">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <a href="{{ route('admin.quotations.show', $quotation) }}?download=pdf" title="Download PDF" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-gray-400 dark:hover:bg-slate-800">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        </a>
                                        <form action="{{ route('admin.quotations.destroy', $quotation) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this quotation?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-gray-400 dark:hover:bg-slate-800">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    No quotations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($quotations) && $quotations->hasPages())
                <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-700">
                    {{ $quotations->withQueryString()->links() }}
                </div>
            @elseif(isset($quotations))
                <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <p class="text-xs text-gray-400 dark:text-gray-500">Showing {{ $quotations->firstItem() }} to {{ $quotations->lastItem() }} of {{ $quotations->total() }} results</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
