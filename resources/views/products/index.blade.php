<x-layouts.public>
    <x-slot:title>Products - Attachment Portal</x-slot:title>

    <div class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold tracking-tight text-slate-950">Product Catalog</h1>
                <p class="mt-2 text-sm text-slate-600">Browse our complete range of premium attachment solutions.</p>
            </div>

            <form method="GET" action="{{ route('public.products.index') }}" class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative flex-1 max-w-md">
                    <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search by code or description..." class="block h-12 w-full rounded-xl border-slate-200 bg-white pl-11 pr-4 text-sm shadow-sm transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="flex items-center gap-3">
                    <select name="category" class="block h-12 rounded-xl border-slate-200 bg-white px-4 text-sm shadow-sm transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}" {{ (request('category') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-800">Search</button>
                    @if(request()->anyFilled(['search', 'category']))
                        <a href="{{ route('public.products.index') }}" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-rose-50">Clear</a>
                    @endif
                </div>
            </form>

            @if($products->count())
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        <a href="{{ route('public.products.show', $product) }}" class="block">
                            <x-product-card
                                :code="$product->product_code"
                                :title="$product->product_description"
                                :category="$product->category?->name ?? ''"
                                :subcategory="$product->subcategory?->name ?? ''"
                                :connectionType="$product->connection?->name ?? ''"
                                :weight="$product->weight ? $product->weight.'kg' : ''"
                                :width="$product->width ? $product->width.'mm' : ''"
                                :showPrice="false"
                                :showQuoteBtn="false" />
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                    <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-slate-700">No products found</h3>
                    <p class="mt-2 text-sm text-slate-500">Try adjusting your search or filter criteria.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.public>
