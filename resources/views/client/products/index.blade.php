<x-layouts.app>
    <x-slot:title>Products - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Client Portal', 'url' => route('client.dashboard')], ['label' => 'Products']]" />

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
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-neutral-100">Products</h1>
                <p class="text-sm text-gray-700 mt-1">Browse our product catalog with wholesale pricing</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
            <form method="GET" action="{{ route('client.products.index') }}" class="space-y-4">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by code, description, or category..." class="block w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <select name="category" class="block rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $id => $name)
                            <option value="{{ $id }}" {{ request('category') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <select name="subcategory" class="block rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="">All Subcategories</option>
                        @foreach($subcategories ?? [] as $id => $name)
                            <option value="{{ $id }}" {{ request('subcategory') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <select name="connection" class="block rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="">All Connections</option>
                        @foreach($connections ?? [] as $id => $name)
                            <option value="{{ $id }}" {{ request('connection') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'category', 'subcategory', 'connection']))
                        <a href="{{ route('client.products.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:hover:bg-neutral-800">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <div id="product-view-table">
            <div class="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-white dark:border-neutral-800 dark:bg-neutral-900/50">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Product Code</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Connection</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Price</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-neutral-800">
                            @forelse($products ?? [] as $product)
                                <tr class="transition-colors hover:bg-rose-50 dark:hover:bg-neutral-900/50">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('client.products.show', $product) }}" class="font-mono text-sm font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">{{ $product->product_code }}</a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-neutral-400">{{ $product->category?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-neutral-400">{{ $product->connection?->name ?? '-' }}</td>
                                    @php $userPrice = $product->productPrices->first(); @endphp
                                    <td class="px-6 py-4 text-sm font-semibold text-black dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format($userPrice?->final_price ?? $product->ddp_price, 2) }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('client.products.show', $product) }}" title="View" class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-white">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                            
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-neutral-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                        <p class="mt-3 text-sm text-gray-400 dark:text-neutral-500">No products found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($products ?? [], 'links'))
                    <div class="border-t border-slate-100 px-6 py-3 dark:border-neutral-800">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-layouts.app>
