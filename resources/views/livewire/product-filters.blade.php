<div>
    <div x-data="{
        localCategory: '{{ $category }}',
        localSortBy: '{{ $sort_by !== '' ? $sort_by : 'product_code_low_high' }}',
        localPagination: '{{ $perPage !== '' ? $perPage : '100' }}',
        localSubcategory: '{{ $subcategory }}',
        localConnection: '{{ $connection }}',
        localMinWeight: 10,
        localMaxWeight: 100,
        init() {
            const parts = String('{{ $machine_weight }}').split('-');
            if (parts.length === 2) {
                const a = parseInt(parts[0], 10);
                const b = parseInt(parts[1], 10);
                if (!isNaN(a)) this.localMinWeight = Math.min(100, Math.max(10, a));
                if (!isNaN(b)) this.localMaxWeight = Math.min(100, Math.max(10, b));
            }
            this.filterSubcategories();
            this.updateWeightSlider();
            this.$watch('localCategory', () => {
                this.filterSubcategories();
            });
            this.$watch('localMinWeight', () => {
                this.updateWeightSlider();
            });
            this.$watch('localMaxWeight', () => {
                this.updateWeightSlider();
            });
            if (typeof window.Livewire !== 'undefined' && window.Livewire.hook) {
                try {
                    window.Livewire.hook('morph.updated', () => {
                        this.updateWeightSlider();
                    });
                } catch (e) { /* morph hook unavailable - range stays reactive via x-model */ }
            }
        },
        updateWeightSlider() {
            let min = Number(this.localMinWeight);
            let max = Number(this.localMaxWeight);
            if (isNaN(min)) min = 10;
            if (isNaN(max)) max = 100;
            min = Math.min(100, Math.max(10, min));
            max = Math.min(100, Math.max(10, max));
            if (min > max) {
                const t = min;
                min = max;
                max = t;
            }
            this.localMinWeight = min;
            this.localMaxWeight = max;
            const range = document.getElementById('weightRange');
            if (range) {
                range.style.left = ((min - 10) / 90 * 100) + '%';
                range.style.right = (100 - (max - 10) / 90 * 100) + '%';
            }
        },
        filterSubcategories() {
            const subSelect = document.getElementById('subcategory');
            if (!subSelect) return;
            Array.from(subSelect.options).forEach(opt => {
                if (!opt.value || opt.dataset.categorySlug === this.localCategory || !this.localCategory) {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                }
            });
            if (this.localSubcategory) {
                const selected = Array.from(subSelect.options).find(opt => opt.value === this.localSubcategory);
                if (selected && selected.style.display === 'none') {
                    this.localSubcategory = '';
                }
            }
        },
        applyFilters() {
            const min = Number(this.localMinWeight) || 10;
            const max = Number(this.localMaxWeight) || 100;
            const lo = Math.min(min, max);
            const hi = Math.max(min, max);
            const weight = (lo <= 10 && hi >= 100) ? '' : (lo + '-' + hi);
            $wire.applyFilters(this.localCategory ?? '', this.localSortBy ?? '', this.localSubcategory ?? '', this.localConnection ?? '', '', weight, String(this.localPagination ?? ''));
        },
        clearAllFilters() {
            this.resetLocals();
            $wire.clearFilters();
        },
        resetLocals() {
            this.localCategory = '';
            this.localSortBy = 'price_low_high';
            this.localPagination = '100';
            this.localSubcategory = '';
            this.localConnection = '';
            this.localMinWeight = 10;
            this.localMaxWeight = 100;
            this.filterSubcategories();
            this.updateWeightSlider();
        },
    }" @filters-cleared.window="resetLocals()">
        <section class="w-full rounded-[22px] border border-gray-100 bg-white px-4 py-5 shadow-sm sm:px-6">

            <h1 class="mb-4 text-xl font-bold tracking-tight text-gray-950 sm:text-2xl">
                Filter your search
            </h1>

            <!-- Top row -->
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">

                <!-- Category -->
                <div class="lg:col-span-3">
                    <label for="category" class="block text-xs font-medium text-gray-500 mb-1.5">
                        Category
                    </label>
                    <div class="relative">
                        <select id="category" x-model="localCategory"
                            class="block h-11 w-full rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            <option value="">All Categories</option>
                            @foreach ($categories ?? [] as $slug => $name)
                                <option value="{{ $slug }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <!-- Attachment connection -->
                <div class="lg:col-span-3">
                    <label for="connection" class="block text-xs font-medium text-gray-500 mb-1.5">
                        Attachment connection
                    </label>
                    <div class="relative">
                        <select id="connection" x-model="localConnection"
                            class="block h-11 w-full rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            <option value="">All Connections</option>
                            @foreach ($connections ?? [] as $slug => $name)
                                <option value="{{ $slug }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <!-- Search -->
                <div class="lg:col-span-6">
                    <label for="search" class="sr-only">Search product code or description</label>
                    <div class="flex h-11 items-center overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <div class="flex min-w-0 flex-1 items-center gap-3 px-4">
                            <svg class="h-5 w-5 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-4.35-4.35m2.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                            </svg>
                            <input id="search" type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search product code or description"
                                class="min-w-0 flex-1 border-0 bg-transparent py-2.5 text-sm text-slate-700 placeholder-slate-400 outline-none focus:ring-0" />
                        </div>
                             <x-ui.button type="button" label="Search"
                             class="m-1.5 rounded-xl bg-black px-7 py-3 text-base font-semibold text-white transition hover:bg-gray-800"
                             @click="applyFilters()"
                        wire:loading.attr="disabled" />
                    </div>
                </div>
            </div>

            <!-- Bottom row -->
            <div class="mt-5 grid grid-cols-1 gap-4 lg:grid-cols-12 lg:items-end">

                <!-- Subcategory -->
                <div class="lg:col-span-3">
                    <label for="subcategory" class="block text-xs font-medium text-gray-500 mb-1.5">
                        Subcategory
                    </label>
                    <div class="relative">
                        <select id="subcategory" x-model="localSubcategory"
                            class="block h-11 w-full rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            <option value="">All Subcategories</option>
                            @foreach ($subcategories ?? [] as $sub)
                                <option value="{{ $sub->slug }}" data-category-slug="{{ $sub->category?->slug }}">
                                    {{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Machine weight range (Alpine-managed: skipped by Livewire morph) -->
                <div class="lg:col-span-3" wire:ignore>
                    <div class="mb-1.5 flex items-center justify-between">
                        <label for="weight" class="block text-xs font-medium text-gray-500">
                            Machine weight
                        </label>
                        <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-700"
                            x-text="localMinWeight + ' - ' + localMaxWeight + ' ton'">10 - 100 ton</span>
                    </div>
                    <div class="px-1 pt-1">
                        <div class="relative h-5">
                            <div class="absolute top-1/2 h-1.5 w-full -translate-y-1/2 rounded-full bg-slate-200"></div>
                            <div id="weightRange" class="absolute top-1/2 h-1.5 -translate-y-1/2 rounded-full bg-slate-700"
                                style="left: 0%; right: 0%;"></div>
                            <input id="minWeight" type="range" min="10" max="100" step="1"
                                x-model.number="localMinWeight" @input="updateWeightSlider()" @change="applyFilters()"
                                aria-label="Minimum machine weight in tons"
                                class="weight-slider absolute inset-0 h-full w-full cursor-pointer" />
                            <input id="maxWeight" type="range" min="10" max="100" step="1"
                                x-model.number="localMaxWeight" @input="updateWeightSlider()" @change="applyFilters()"
                                aria-label="Maximum machine weight in tons"
                                class="weight-slider absolute inset-0 h-full w-full cursor-pointer" />
                        </div>
                        <div class="mt-1 flex justify-between text-xs text-slate-500">
                            <span>10 ton</span>
                            <span>100 ton</span>
                        </div>
                    </div>
                </div>

                <!-- Sort -->
                <div class="lg:col-span-3">
                    <label for="sort" class="block text-xs font-medium text-gray-500 mb-1.5">
                        Sort by
                    </label>
                    <div class="relative">
                        <select id="sort" x-model="localSortBy"
                            class="block h-11 w-full rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            @foreach ($sortOptions as $option)
                                <option value="{{ $option['value'] }}">{{ $option['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Rows per page -->
                <div class="lg:col-span-3">
                    <label for="perPage" class="block text-xs font-medium text-gray-500 mb-1.5">
                        Rows per page
                    </label>
                    <div class="relative">
                        <select id="perPage" x-model="localPagination" @change="applyFilters()"
                            class="block h-11 w-full rounded-lg border border-gray-200 bg-white px-3 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            @foreach ($pageOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="mt-5 flex flex-wrap items-center gap-3">
                   <x-ui.button type="button" variant="black" label="Apply Filters"  class="rounded-xl bg-black px-7 py-4 text-base font-semibold text-white transition hover:bg-gray-800 active:scale-[0.99]" @click="applyFilters()"
                        wire:loading.attr="disabled" />
                @if ($search || $sort_by || $machine_weight || $category || $subcategory || $connection || $machine_class)
                    <x-ui.button type="button" variant="red" label="Clear Filters" @click="clearAllFilters()"
                        wire:loading.attr="disabled" />
                @endif
            </div>

        </section>
    </div>

    <div wire:loading.delay.longest wire:target="applyFilters, search, sort_by"
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-white/95">
        <svg class="h-16 w-auto animate-pulse" viewBox="0 0 120 40" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <text x="0" y="32" font-family="Inter, system-ui, sans-serif" font-size="32" font-weight="800"
                fill="#0b5cab" letter-spacing="-0.5">BWT</text>
        </svg>
    </div>

    <div wire:loading.remove.delay.longest wire:target="applyFilters, search, sort_by">
        @if ($products->count())
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($products as $product)
                    <x-product.product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs text-slate-500">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                    ({{ $products->perPage() }} rows per page)
                </p>
                @if ($products->hasPages())
                    <div>
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
