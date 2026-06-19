<x-layouts.app>
    <x-slot:title>Categories - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => route('admin.dashboard')], ['label' => 'Categories']]" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">Categories</h1>
                <p class="text-sm text-gray-700 mt-1">Manage categories and subcategories</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Add Category
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
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">All Categories</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700 bg-rose-50 dark:bg-slate-800/50">
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Products</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Subcategories</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($categories ?? [] as $category)
                            <tr class="hover:bg-rose-50 dark:bg-slate-800/50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-black dark:text-gray-100">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-400 font-mono text-xs">{{ $category->slug }}</td>
                                <td class="px-6 py-4">
                                    @if(isset($category->status) && $category->status === 'inactive')
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-300">Inactive</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Active</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ $category->products_count ?? $category->products->count() ?? 0 }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ $category->subcategories->count() }}</td>
                                <td class="px-6 py-4 text-gray-700 dark:text-gray-400">{{ $category->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.categories.edit', $category) }}" title="Edit" class="inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-gray-400 dark:hover:bg-slate-800">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                          <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
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
                                    No categories found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(isset($categories) && $categories instanceof \Illuminate\Pagination\LengthAwarePaginator && $categories->hasPages())
                <div class="px-6 py-3 border-t border-slate-100 dark:border-slate-700">
                    {{ $categories->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
