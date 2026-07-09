<x-layouts.public>
    <x-slot:title>BWT | Wholesale Attachment Product Database</x-slot:title>

    <section class="text-center pt-10 pb-6 px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Wholesale Attachment Product Database</h1>
        <p class="text-gray-600 mt-2 text-lg">
            Browse Excavator attachments, Wheel Loader attachments, Wear- and Spare parts. Reseller login required to
            view prices.
        </p>
    </section>

    <main class="bg-gray-100 pt-8 pb-14">
        <div class="max-w-[1700px] mx-auto px-4 sm:px-8">

            <form method="GET" action="{{ url('/') }}"
                class="flex items-stretch overflow-hidden rounded-xl border border-gray-300 bg-white shadow-sm mb-4">
                <div class="flex flex-1 items-center gap-3 px-5">
                    <svg class="h-5 w-5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input name="search" type="text" value="{{ request('search') }}"
                        placeholder="Search product code or description"
                        class="min-w-0 flex-1 border-0 bg-transparent py-3.5 sm:py-4 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-0" />
                </div>
                <button type="submit"
                    class="bg-black px-6 sm:px-8 text-sm sm:text-base font-medium text-white whitespace-nowrap transition-colors hover:bg-gray-800">
                    Search
                </button>
            </form>

            <div x-data="{ filtersOpen: window.innerWidth >= 1024 }" class="bg-white rounded-xl shadow-sm mb-8">
                <div class="flex items-center justify-between px-5 py-3 sm:px-6 lg:hidden border-b border-gray-100">
                    <span class="text-sm font-semibold text-gray-800">Filters</span>
                    <button @click="filtersOpen = !filtersOpen"
                        class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                        <span x-text="filtersOpen ? 'Hide' : 'Show'"></span>
                        <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': filtersOpen }" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <form method="GET" action="{{ url('/') }}" x-show="filtersOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2">
                    <div class="p-5 sm:p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Category</label>
                                    <select id="filterCategory" name="category"
                                        class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                                        <option value="">All Categories</option>
                                        @foreach ($categories ?? [] as $slug => $name)
                                            <option value="{{ $slug }}"
                                                {{ (string) request('category') === (string) $slug ? 'selected' : '' }}>
                                                {{ $name }}</option>
                                        @endforeach
                                    </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Subcategory</label>
                                    <select id="filterSubcategory" name="subcategory"
                                        class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                                        <option value="">All Subcategories</option>
                                        @foreach ($subcategories ?? [] as $sub)
                                            <option value="{{ $sub->slug }}" data-category-slug="{{ $sub->category?->slug }}"
                                                {{ (string) request('subcategory') === (string) $sub->slug ? 'selected' : '' }}>
                                                {{ $sub->name }}</option>
                                        @endforeach
                                    </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Connection</label>
                                    <select name="connection"
                                        class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                                        <option value="">All Connections</option>
                                        @foreach ($connections ?? [] as $slug => $name)
                                            <option value="{{ $slug }}"
                                                {{ (string) request('connection') === (string) $slug ? 'selected' : '' }}>
                                                {{ $name }}</option>
                                        @endforeach
                                    </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5">Machine Weight</label>
                                <select name="machine_class"
                                    class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                                    <option value="">All Weights</option>
                                    <option value="0-10" {{ request('machine_class') == '0-10' ? 'selected' : '' }}>0
                                        - 10 t</option>
                                    <option value="10-20" {{ request('machine_class') == '10-20' ? 'selected' : '' }}>
                                        10 - 20 t</option>
                                    <option value="20-30" {{ request('machine_class') == '20-30' ? 'selected' : '' }}>
                                        20 - 30 t</option>
                                    <option value="30-50" {{ request('machine_class') == '30-50' ? 'selected' : '' }}>
                                        30 - 50 t</option>
                                    <option value="50-100"
                                        {{ request('machine_class') == '50-100' ? 'selected' : '' }}>50 - 100 t
                                    </option>
                                    <option value="100+" {{ request('machine_class') == '100+' ? 'selected' : '' }}>
                                        100+ t</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <button type="submit"
                                class="inline-flex items-center gap-2 bg-black hover:bg-gray-800 transition-colors text-white font-medium text-sm px-6 py-2.5 rounded-lg">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3" />
                                </svg>
                                Apply Filters
                            </button>
                            @if (request()->anyFilled(['search', 'category', 'subcategory', 'connection', 'machine_class']))
                                <a href="{{ url('/') }}"
                                    class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 transition-colors text-white font-medium text-sm px-6 py-2.5 rounded-lg no-underline">
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            @if ($products->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        @php $img = $product->getFirstMediaUrl('images'); @endphp
                        <div class="product-card bg-white rounded-md shadow-sm overflow-hidden flex flex-col">
                            <div class="p-6 pb-2 flex items-center justify-center">
                                @if ($img)
                                    <img src="{{ $img }}" alt="{{ $product->product_title }}"
                                        class="h-40 object-contain" />
                                @else
                                    <div class="h-40 flex items-center justify-center text-gray-400 text-sm">No Image
                                    </div>
                                @endif
                            </div>
                            <div class="px-6 pt-2 flex-1 flex flex-col">
                                <h3 class="font-bold text-[15px] text-gray-900 leading-snug">
                                    {{ $product->product_title }}</h3>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @if ($product->category)
                                        <span
                                            class="bg-blue-50 text-bwtblue text-xs font-medium px-2.5 py-1 rounded">{{ $product->category->name }}</span>
                                    @endif
                                    @if ($product->subcategory)
                                        <span
                                            class="bg-green-50 text-green-700 text-xs font-medium px-2.5 py-1 rounded">{{ $product->subcategory->name }}</span>
                                    @endif
                                </div>

                                <div class="text-sm text-gray-700 space-y-0.5 mb-4">

                                    @if ($product->machine_class)
                                        <p><span class="text-gray-500">Machine Class:</span>
                                            {{ $product->machine_class }} t</p>
                                    @endif
                                    @if ($product->connection)
                                        <p><span class="text-gray-500">Connection:</span>
                                            {{ $product->connection->name }}</p>
                                    @endif
                                    @if ($product->weight)
                                        <p>
                                            <span class="text-gray-500">Weight:</span>
                                            {{ rtrim(rtrim(number_format($product->weight, 2, '.', ''), '0'), '.') }}
                                            kg
                                        </p>
                                    @endif
                                    @if ($product->width)
                                        <p><span class="text-gray-500">Width:</span>
                                            {{ rtrim(rtrim(number_format($product->width, 2, '.', ''), '0'), '.') }} mm
                                        </p>
                                    @endif
                                    @if ($product->volume)
                                        <p><span class="text-gray-500">Volume:</span>
                                            {{ rtrim(rtrim(number_format($product->volume, 2, '.', ''), '0'), '.') }}
                                            m³</p>
                                    @endif
                                </div>

                                <a href="{{ route('public.products.show', $product) }}"
                                    class="mt-auto w-full bg-bwtblue hover:bg-bwtblue2 transition-colors text-white font-medium py-3 rounded text-center no-underline block">
                                    View details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center text-gray-500 py-16 text-lg">
                    No products match your search.
                </div>
            @endif

        </div>
    </main>

    <script>
        document.getElementById('filterCategory')?.addEventListener('change', function() {
            const selected = this.value;
            const subSelect = document.getElementById('filterSubcategory');
            const options = subSelect.querySelectorAll('option');
            options.forEach(opt => {
                if (!opt.value || opt.dataset.categorySlug === selected || !selected) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                }
            });
            subSelect.value = '';
        });
    </script>

</x-layouts.public>
