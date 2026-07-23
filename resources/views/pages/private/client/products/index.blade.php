<x-layouts.app>
    <x-slot:title>Manage Products - {{ $siteTitle }}</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Wholesaler Portal', 'url' => route('client.dashboard')], ['label' => 'Products']]" />

    <div class="space-y-6">
        <x-ui.hero title="Products" subtitle="Manage your product catalog">
            <x-slot:actions>

            </x-slot:actions>
        </x-ui.hero>

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

        <form method="GET" action="{{ route('client.products.index') }}"
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 dark:text-neutral-400 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name or code..."
                        class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-neutral-400 mb-1">Category</label>
                    <select name="category"
                        class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="">All Categories</option>
                        @foreach ($categories ?? [] as $id => $name)
                            <option value="{{ $id }}" @selected(request('category') == $id)>{{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        class="block text-xs font-medium text-slate-500 dark:text-neutral-400 mb-1">Subcategory</label>
                    <select name="subcategory"
                        class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="">All Subcategories</option>
                        @foreach ($subcategories ?? [] as $id => $name)
                            <option value="{{ $id }}" @selected(request('subcategory') == $id)>{{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label
                        class="block text-xs font-medium text-slate-500 dark:text-neutral-400 mb-1">Connection</label>
                    <select name="connection"
                        class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="">All Connections</option>
                        @foreach ($connections ?? [] as $id => $name)
                            <option value="{{ $id }}" @selected(request('connection') == $id)>{{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-500 dark:text-neutral-400 mb-1">Machine Class
                        (t)</label>
                    <input type="text" name="machine_class" value="{{ request('machine_class') }}"
                        placeholder="e.g. 22, 30, 45"
                        class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500">
                </div>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 mt-4">
                <div class="flex items-center gap-2">
                    <select name="status"
                        class="block w-auto rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="">All Status</option>
                        <option value="published" @selected(request('status') === 'published')>Published</option>
                        <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                        <option value="hidden" @selected(request('status') === 'hidden')>Hidden</option>
                    </select>
                    <select name="per_page"
                        class="block w-auto rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100">
                        <option value="15" @selected(request('per_page', 15) == 15)>15</option>
                        <option value="25" @selected(request('per_page') == 25)>25</option>
                        <option value="50" @selected(request('per_page') == 50)>50</option>
                        <option value="100" @selected(request('per_page') == 100)>100</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('client.products.index') }}" wire:navigate
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                        Clear
                    </a>
                </div>
            </div>
        </form>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-neutral-800">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">All Products</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr
                            class="border-b border-slate-100 bg-slate-50/50 dark:border-neutral-800 dark:bg-neutral-900/50">
                            <th
                                class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-neutral-400">
                                Product</th>
                            <th
                                class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-neutral-400">
                                Code</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">
                                Price</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-neutral-500">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-neutral-800">
                        @forelse($products ?? [] as $product)
                            <tr class="transition-colors hover:bg-slate-50/80 dark:hover:bg-neutral-800/30">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="aspect-[3/2] w-10 shrink-0 overflow-hidden rounded-lg bg-slate-100 dark:bg-neutral-800">
                                            @if ($product->getFirstMediaUrl('images'))
                                                <img src="{{ $product->getFirstMediaUrl('images') }}"
                                                    class="h-full w-full object-contain">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center">
                                                    <svg class="h-5 w-5 text-slate-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('client.products.show', $product) }}" wire:navigate
                                            class="font-medium text-slate-900 hover:text-emerald-600 dark:text-white dark:hover:text-emerald-400">{{ $product->product_title }}</a>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <code
                                        class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-mono text-slate-600 dark:bg-neutral-800 dark:text-neutral-300">{{ $product->product_code }}</code>
                                </td>
                                @php
                                    $pricingService = app(\App\Services\ProductPricingService::class);
                                    $price = $pricingService->getPrice($product);
                                    $isFavorited =
                                        auth()->check() &&
                                        $product
                                            ->favoritedByUsers()
                                            ->where('user_id', auth()->id())
                                            ->exists();
                                @endphp
                                <td class="px-5 py-4 font-medium text-slate-900 dark:text-white">
                                    {{ config('app.currency_symbol') }}{{ number_format($price, 2) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        @auth
                                            <x-product.favorite-button :product="$product" :is-favorited="$isFavorited" />
                                        @endauth
                                        <a href="{{ route('client.products.show', $product) }}" wire:navigate
                                            title="View"
                                            class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-white">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-neutral-500/50"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <p class="mt-3 text-sm text-gray-400 dark:text-neutral-500">No products found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (isset($products) && $products->hasPages())
                <div class="border-t border-slate-100 px-5 py-4 dark:border-neutral-800">
                    {{ $products->withQueryString()->links() }}
                </div>
            @elseif(isset($products) && $products->total() > 0)
                <div class="border-t border-slate-100 px-5 py-4 dark:border-neutral-800">
                    <p class="text-xs text-slate-400 dark:text-neutral-500">
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                        {{ $products->total() }} results
                    </p>
                </div>
            @endif
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('assets/js/product.js') }}"></script>
    @endpush
</x-layouts.app>
