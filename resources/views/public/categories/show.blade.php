<x-layouts.public>
    <x-slot:title>{{ $category->name }} - Attachment Portal</x-slot:title>

    <div class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('public.products.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-emerald-600 hover:text-emerald-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    All Products
                </a>
            </div>

            <div class="mb-8">
                <h1 class="text-3xl font-bold tracking-tight text-slate-950">{{ $category->name }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $products->count() }} products</p>
            </div>

            @if($products->count())
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        <a href="{{ route('public.products.show', $product) }}" class="block">
                            <x-product-card
                                :image="$product->getFirstMediaUrl('images', 'small')"
                                :code="$product->product_code"
                                :title="$product->product_description"
                                :category="$category->name"
                                :subcategory="$product->subcategory?->name ?? ''"
                                :connectionType="$product->connection?->name ?? ''"
                                :weight="$product->weight ? $product->weight.'kg' : ''"
                                :width="$product->width ? $product->width.'mm' : ''"
                                :showPrice="false"
                                :showQuoteBtn="false" />
                        </a>
                    @endforeach
                </div>

                @if(method_exists($products, 'links'))
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                    <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-slate-700">No products in this category</h3>
                    <p class="mt-2 text-sm text-slate-500">Check back later or browse other categories.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.public>
