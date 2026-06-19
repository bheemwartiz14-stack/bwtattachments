<x-layouts.app>
    <x-slot:title>Connections - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => route('admin.dashboard')], ['label' => 'Connections']]" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">Connection Types</h1>
                <p class="text-sm text-gray-700 mt-1">Manage connection types for products</p>
            </div>
            <a href="{{ route('admin.connections.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Add Connection
            </a>
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

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700 bg-rose-50 dark:bg-slate-800/50">
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($connections ?? [] as $connection)
                            <tr class="hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-black dark:text-gray-100">{{ $connection->name }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-400 font-mono text-xs">{{ $connection->slug }}</td>
                                <td class="px-6 py-4">
                                    @if($connection->trashed())
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/50 dark:text-red-300">Deleted</span>
                                    @elseif(isset($connection->status) && $connection->status === 'inactive')
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-300">Inactive</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Active</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ $connection->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.connections.edit', $connection) }}" title="Edit" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-gray-400 dark:hover:bg-slate-800">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        @if($connection->trashed())
                                            <form action="{{ route('admin.connections.restore', $connection) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" title="Restore" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-gray-400 dark:hover:bg-slate-800">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.connections.destroy', $connection) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this connection type?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-gray-400 dark:hover:bg-slate-800">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400 dark:text-gray-500">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                    No connection types found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($connections) && $connections instanceof \Illuminate\Pagination\LengthAwarePaginator && $connections->hasPages())
                <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-700">
                    {{ $connections->withQueryString()->links() }}
                </div>
            @elseif(isset($connections) && $connections instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                    <p class="text-xs text-gray-400 dark:text-gray-500">Showing {{ $connections->firstItem() }} to {{ $connections->lastItem() }} of {{ $connections->total() }} results</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
