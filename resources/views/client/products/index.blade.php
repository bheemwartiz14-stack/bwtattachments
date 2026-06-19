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

    <div x-data="productBrowser()" class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">Products</h1>
                <p class="text-sm text-gray-700 mt-1">Browse our product catalog with wholesale pricing</p>
            </div>
            <div class="flex items-center gap-2">
                <button @@click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-slate-900 text-white dark:bg-emerald-600' : 'bg-white text-gray-700 dark:bg-slate-800 dark:text-gray-400'" class="rounded-lg border border-slate-200 p-2 shadow-sm transition-colors hover:bg-rose-50 dark:border-slate-600 dark:hover:bg-slate-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                </button>
                <button @@click="viewMode = 'table'" :class="viewMode === 'table' ? 'bg-slate-900 text-white dark:bg-emerald-600' : 'bg-white text-gray-700 dark:bg-slate-800 dark:text-gray-400'" class="rounded-lg border border-slate-200 p-2 shadow-sm transition-colors hover:bg-rose-50 dark:border-slate-600 dark:hover:bg-slate-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                </button>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <form method="GET" action="{{ route('client.products.index') }}" class="space-y-4">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by code, description, or category..." class="block w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <select name="category" class="block rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select name="subcategory" class="block rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                        <option value="">All Subcategories</option>
                        @foreach($subcategories ?? [] as $subcategory)
                            <option value="{{ $subcategory->id }}" {{ request('subcategory') == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                        @endforeach
                    </select>
                    <select name="connection" class="block rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                        <option value="">All Connections</option>
                        @foreach($connections ?? [] as $connection)
                            <option value="{{ $connection->id }}" {{ request('connection') == $connection->id ? 'selected' : '' }}>{{ $connection->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'category', 'subcategory', 'connection']))
                        <a href="{{ route('client.products.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">Clear</a>
                    @endif
                </div>
            </form>
        </div>

        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($products ?? [] as $product)
                <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900 group transition-all duration-200 hover:shadow-md">
                    <a href="{{ route('client.products.show', $product) }}">
                        <div class="aspect-[4/3] bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_code }}" class="w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-gray-400 dark:text-gray-500">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                            @if($product->category)
                                <div class="absolute top-2 left-2">
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">{{ $product->category->name }}</span>
                                </div>
                            @endif
                        </div>
                    </a>
                    <div class="p-4">
                        <p class="text-xs font-mono font-medium text-emerald-600 dark:text-emerald-400">{{ $product->product_code }}</p>
                        <h3 class="text-sm font-semibold text-black dark:text-gray-100 mt-1 line-clamp-2">{{ $product->product_description }}</h3>
                        <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-700 dark:text-gray-400">
                            @if($product->weight)
                                <span>{{ $product->weight }}kg</span>
                            @endif
                            @if($product->width)
                                <span>{{ $product->width }}mm</span>
                            @endif
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
                            <p class="text-lg font-bold text-black dark:text-gray-100">${{ number_format($product->ddp_price, 2) }}</p>
                            <button @@click="$dispatch('add-to-quote', { id: {{ $product->id }}, code: '{{ $product->product_code }}', name: '{{ addslashes($product->product_description) }}', price: {{ $product->ddp_price }} })"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <p class="mt-4 text-sm font-medium text-gray-500 dark:text-gray-400">No products found</p>
                    <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">Try adjusting your search or filter criteria</p>
                </div>
            @endforelse
        </div>

        <div x-show="viewMode === 'table'" class="overflow-hidden rounded-xl border border-slate-100 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-white dark:border-slate-700 dark:bg-slate-800/50">
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Product Code</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Connection</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Price</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($products ?? [] as $product)
                            <tr class="transition-colors hover:bg-rose-50 dark:hover:bg-slate-800/50">
                                <td class="px-6 py-4">
                                    <a href="{{ route('client.products.show', $product) }}" class="font-mono text-sm font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">{{ $product->product_code }}</a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400 max-w-xs truncate">{{ $product->product_description }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">{{ $product->category?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400">{{ $product->connection?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-black dark:text-gray-100">${{ number_format($product->ddp_price, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button @@click="$dispatch('add-to-quote', { id: {{ $product->id }}, code: '{{ $product->product_code }}', name: '{{ addslashes($product->product_description) }}', price: {{ $product->ddp_price }} })"
                                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                        Add to Quotation
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500/50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    <p class="mt-3 text-sm text-gray-400 dark:text-gray-500">No products found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($products ?? [], 'links'))
                <div class="border-t border-slate-100 px-6 py-3 dark:border-slate-700">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

        @if(method_exists($products ?? [], 'links') && $products->hasPages())
            <div class="flex justify-center">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <script>
        function productBrowser() {
            return {
                viewMode: 'grid'
            }
        }
    </script>
</x-layouts.app>
