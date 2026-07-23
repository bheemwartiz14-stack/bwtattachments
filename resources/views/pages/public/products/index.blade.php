<x-layouts.public>
    <x-slot:title>Products - BWT</x-slot:title>

    <main class="min-h-screen bg-gradient-to-b from-slate-50 to-white">
        <div class="max-w-[1700px] mx-auto px-6 lg:px-8 py-8">

            {{-- Hero --}}
            <div class="text-center mb-10">
                <h1 class="text-3xl lg:text-4xl font-bold text-slate-900">Attachment Product Database</h1>
                <p class="mt-3 text-slate-500 max-w-2xl mx-auto leading-relaxed">
                    Browse Excavator Attachments, Wheel Loader Attachments and Wear Parts. Login required to view prices and download technical PDFs.
                </p>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">

                {{-- Sidebar Filters --}}
                <aside class="lg:w-72 flex-shrink-0">
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 sticky top-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-slate-900">Filters</h3>
                            @if(request()->anyFilled(['category', 'subcategory', 'connection', 'search']))
                                <a href="{{ route('public.products.index') }}" wire:navigate class="text-xs font-medium text-red-500 hover:text-red-600 no-underline">Clear all</a>
                            @endif
                        </div>

                        <form method="GET" action="{{ route('public.products.index') }}" id="filter-form" class="space-y-5">

                            {{-- Category --}}
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Category</label>
                                <div class="space-y-1.5">
                                    @foreach($categories ?? [] as $slug => $name)
                                        <label class="flex items-center gap-2.5 cursor-pointer group">
                                            <input type="radio" name="category" value="{{ $slug }}"
                                                   class="category-radio w-4 h-4 text-bwtblue border-slate-300 focus:ring-bwtblue/30"
                                                   {{ (string)request('category') === (string)$slug ? 'checked' : '' }}>
                                            <span class="text-sm text-slate-700 group-hover:text-slate-900 transition-colors">{{ $name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Subcategory --}}
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Subcategory</label>
                                <div id="subcategory-list" class="space-y-1.5 max-h-48 overflow-y-auto scrollbar-thin">
                                    @foreach($subcategories ?? [] as $subcategory)
                                        <label class="subcategory-item flex items-center gap-2.5 cursor-pointer group"
                                               data-category-slug="{{ $subcategory->category?->slug }}">
                                            <input type="checkbox" name="subcategory[]" value="{{ $subcategory->slug }}"
                                                   class="w-4 h-4 text-bwtblue border-slate-300 rounded focus:ring-bwtblue/30"
                                                   {{ in_array((string)$subcategory->slug, (array)request('subcategory', [])) ? 'checked' : '' }}>
                                            <span class="text-sm text-slate-700 group-hover:text-slate-900 transition-colors">{{ $subcategory->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Connection --}}
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-2">Connection</label>
                                <div class="space-y-1.5">
                                    @foreach($connections ?? [] as $slug => $name)
                                        <label class="flex items-center gap-2.5 cursor-pointer group">
                                            <input type="checkbox" name="connection[]" value="{{ $slug }}"
                                                   class="w-4 h-4 text-bwtblue border-slate-300 rounded focus:ring-bwtblue/30"
                                                   {{ in_array($slug, (array)request('connection', [])) ? 'checked' : '' }}>
                                            <span class="text-sm text-slate-700 group-hover:text-slate-900 transition-colors">{{ $name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full bg-bwtblue hover:bg-bwtblue2 text-white text-sm font-semibold py-2.5 rounded-xl transition-all duration-200 cursor-pointer">
                                Apply Filters
                            </button>
                        </form>
                    </div>
                </aside>

                {{-- Products --}}
                <div class="flex-1 min-w-0">

                    {{-- Search Bar --}}
                    <form method="GET" action="{{ route('public.products.index') }}"
                          class="bg-white rounded-2xl border border-slate-200 p-4 mb-6 flex gap-3 items-center shadow-sm">
                        <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search product code or description..."
                               class="flex-1 border-0 bg-transparent text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-0">
                        <button type="submit"
                                class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-1.5 rounded-xl transition-colors cursor-pointer">
                            Search
                        </button>
                        @if(request()->anyFilled(['search', 'category', 'subcategory', 'connection']))
                            <a href="{{ route('public.products.index') }}"
                             wire:navigate  class="text-xs text-slate-400 hover:text-red-500 no-underline transition-colors">Clear</a>
                        @endif
                    </form>

                    @if($products->count())
                        {{-- Results Count --}}
                        <p class="text-sm text-slate-500 mb-4">{{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }} found</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                            @foreach($products as $product)
                                @php
                                    $img = $product->getFirstMediaUrl('images', 'small');
                                @endphp
                                @php
                                    $quoteUrl = null;
                                    if (auth()->check()) {
                                        $user = auth()->user();
                                        if ($user->hasRole('Wholesaler')) {
                                            $quoteUrl = route('client.quotations.create', ['product_id' => $product->id]);
                                        } elseif ($user->hasRole('Reseller') || $user->hasRole('Retailer')) {
                                            $quoteUrl = route('reseller.quotations.create', ['product_id' => $product->id]);
                                        }
                                    }
                                @endphp
                                <x-product-card
                                    :image="$img"
                                    :title="$product->product_title ?? $product->product_description ?? 'Product'"
                                    :code="$product->product_code ?? 'N/A'"
                                    :category="$product->category?->name"
                                    :subcategory="$product->subcategory?->name"
                                    :connectionType="$product->connection?->name"
                                    :weight="$product->weight ? $product->weight . ' kg' : null"
                                    :width="$product->width ? $product->width . ' mm' : null"
                                    :machineClass="$product->machine_class ? $product->machine_class . ' t' : null"
                                    detailsUrl="{{ route('public.products.show', $product) }}"
                                    :productId="$product->id"
                                    :favorited="in_array($product->id, $favoritedIds ?? [])"
                                    :quoteUrl="$quoteUrl"
                                />
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-20 px-6 bg-white rounded-2xl border border-slate-200">
                            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.75v2.25a.75.75 0 01-.75.75h-3m6.75 3l-3 3m0 0l-3-3m3 3V3m-3 16.5h12a.75.75 0 00.75-.75v-12a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v12a.75.75 0 00.75.75z"/>
                            </svg>
                            <h3 class="text-lg font-semibold text-slate-900 mb-1">No products found</h3>
                            <p class="text-sm text-slate-500">Try adjusting your search or filter criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script>
        (function() {
            var categoryRadios = document.querySelectorAll('.category-radio');
            var subcategoryItems = document.querySelectorAll('.subcategory-item');

            function filterSubcategories() {
                var selected = null;
                categoryRadios.forEach(function(rb) {
                    if (rb.checked) selected = rb.value;
                });

                subcategoryItems.forEach(function(item) {
                    var catSlug = item.getAttribute('data-category-slug');
                    if (selected === null) {
                        item.style.display = '';
                    } else {
                        item.style.display = selected === catSlug ? '' : 'none';
                        if (selected !== catSlug) {
                            var checkbox = item.querySelector('input[type="checkbox"]');
                            if (checkbox) checkbox.checked = false;
                        }
                    }
                });
            }

            categoryRadios.forEach(function(rb) {
                rb.addEventListener('change', filterSubcategories);
            });

            filterSubcategories();
        })();
    </script>
</x-layouts.public>
