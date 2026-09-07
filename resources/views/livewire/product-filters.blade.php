<div>
    <div class="flex items-stretch overflow-hidden rounded-xl border border-gray-300 bg-white shadow-sm mb-3">
        <div class="flex flex-1 items-center gap-3 px-4">
            <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Search product code or description"
                class="min-w-0 flex-1 border-0 bg-transparent py-2.5 text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-0" />
        </div>
        <x-ui.button type="submit" class="bg-black px-5 text-sm font-medium text-white whitespace-nowrap transition-colors hover:bg-gray-800" variant="black" label="Search" />

    </div>

    <div x-data="{
        filtersOpen: window.innerWidth >= 1024,
        localCategory: '{{ $category }}',
        localSortBy: '{{ $sort_by !== '' ? $sort_by : 'newest' }}',
        localPagination: '{{ $perPage !== '' ? $perPage : '25' }}',
        localSubcategory: '{{ $subcategory }}',
        localConnection: '{{ $connection }}',
        localMachineClass: '{{ $machine_class }}',
        localMinWeight: '{{ $min_weight !== '' ? $min_weight : 0 }}',
        localMaxWeight: '{{ $max_weight !== '' ? $max_weight : 10000 }}',
        init() {
            this.filterSubcategories();
            this.updateWeightSlider();
            this.$watch('localCategory', (value, oldValue) => {
                this.filterSubcategories();
            });
        },
        filterSubcategories() {
            const subSelect = document.getElementById('filterSubcategory');
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
            $wire.applyFilters(this.localCategory ?? '', this.localSortBy ?? '', this.localSubcategory ?? '', this.localConnection ?? '', this.localMachineClass ?? '', String(this.localMinWeight ?? ''), String(this.localMaxWeight ?? ''), String(this.localPagination ?? ''));
        },
        updateWeightSlider() {
            let min = Number(this.localMinWeight);
            let max = Number(this.localMaxWeight);
            if (isNaN(min)) min = 0;
            if (isNaN(max)) max = 10000;
            min = Math.min(10000, Math.max(0, min));
            max = Math.min(10000, Math.max(0, max));
            if (min > max) min = max;
            this.localMinWeight = min;
            this.localMaxWeight = max;
            const range = document.getElementById('weightRange');
            if (range) {
                range.style.left = (min / 10000 * 100) + '%';
                range.style.right = (100 - max / 10000 * 100) + '%';
            }
        }
    }" class="bg-white rounded-xl shadow-sm mb-8">
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

        <div x-show="filtersOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2">
            <div class="p-4 space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <!-- Added the Short By With the functionalty  -->
                     <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Sort by</label>
                        <select x-model="localSortBy"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            @foreach ($sortOptions as $option)
                                <option value="{{ $option['value'] }}">{{ $option['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Added the New fucntion Weight Slider-->
                     <div>
                         <label class="block text-xs font-medium text-gray-500 mb-1.5">Weight</label>
                          <div class="px-2 pt-2">
                            <div class="relative h-6">
                                <div id="weightRange"
                                    class="absolute top-1/2 h-1 -translate-y-1/2 rounded-full bg-blue-600"
                                    style="left: 0%; right: 0%;"></div>
                                <input id="minWeightSlider" type="range" min="0" max="10000" step="100" x-model.number="localMinWeight" @input="updateWeightSlider()"
                                    class="weight-slider absolute inset-0 w-full" />
                                <input id="maxWeightSlider" type="range" min="0" max="10000" step="100" x-model.number="localMaxWeight" @input="updateWeightSlider()"
                                    class="weight-slider absolute inset-0 w-full" />
                            </div>
                            <div class="mt-1 flex justify-between gap-3">
                                <div class="flex-1">
                                    <div
                                        class="rounded-md bg-white px-3 py-2 text-sm text-slate-600 shadow-sm"
                                        x-text="Number(localMinWeight).toLocaleString() + ' kg'">
                                        0 kg
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <div
                                        class="rounded-md bg-white px-3 py-2 text-sm text-slate-600 shadow-sm"
                                        x-text="Number(localMaxWeight).toLocaleString() + ' kg'">
                                        10,000 kg
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Create a New function Select Options New page -->
                        <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Per page</label>
                        <select x-model="localPagination"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            @foreach ($pageOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    
                        <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Category</label>
                        <select x-model="localCategory" id="filterCategory"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            <option value="">All Categories</option>
                            @foreach ($categories ?? [] as $slug => $name)
                                <option value="{{ $slug }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Subcategory</label>
                        <select x-model="localSubcategory" id="filterSubcategory"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            <option value="">All Subcategories</option>
                            @foreach ($subcategories ?? [] as $sub)
                                <option value="{{ $sub->slug }}" data-category-slug="{{ $sub->category?->slug }}">
                                    {{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Connection</label>
                        <select x-model="localConnection"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            <option value="">All Connections</option>
                            @foreach ($connections ?? [] as $slug => $name)
                                <option value="{{ $slug }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5">Machine Weight</label>
                        <select x-model="localMachineClass"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            <option value="">All Weights</option>
                            <option value="0-10">0 - 10 t</option>
                            <option value="10-20">10 - 20 t</option>
                            <option value="20-30">20 - 30 t</option>
                            <option value="30-50">30 - 50 t</option>
                            <option value="50-100">50 - 100 t</option>
                            <option value="100+">100+ t</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <x-ui.button type="button" variant="black" label="Apply Filters" @click="applyFilters()" wire:loading.attr="disabled" />
                    @if ($search || $sort_by || $min_weight || $max_weight || $category || $subcategory || $connection || $machine_class)
                        <x-ui.button type="button" variant="red" label="Clear Filters" wire:click="clearFilters" wire:loading.attr="disabled" />
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.delay.longest wire:target="applyFilters, search, sort_by"
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-white/95">
        <svg class="h-16 w-auto animate-pulse" viewBox="0 0 120 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <text x="0" y="32" font-family="Inter, system-ui, sans-serif" font-size="32" font-weight="800"
                fill="#0b5cab" letter-spacing="-0.5">BWT</text>
        </svg>
    </div>

    <div wire:loading.remove.delay.longest wire:target="applyFilters, search, sort_by">
        @if ($products->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($products as $product)
                    <x-product.product-card :product="$product" />
                @endforeach
            </div>

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
