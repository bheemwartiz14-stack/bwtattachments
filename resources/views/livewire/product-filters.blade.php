<div>
    <div x-data="{
        localCategory: '{{ $category }}',
        localSortBy: '{{ $sort_by !== '' ? $sort_by : 'price_low_high' }}',
        localPagination: '{{ $perPage !== '' ? $perPage : '25' }}',
        localSubcategory: '{{ $subcategory }}',
        localConnection: '{{ $connection }}',
        localMachineWeight: {{ is_numeric($machine_weight) && (int) $machine_weight >= 10 && (int) $machine_weight <= 100 ? (int) $machine_weight : 10 }},
        init() {
            this.filterSubcategories();
            this.updateFill();
            this.$watch('localCategory', () => {
                this.filterSubcategories();
            });
            this.$watch('localMachineWeight', () => {
                this.updateFill();
            });
            if (typeof window.Livewire !== 'undefined' && window.Livewire.hook) {
                try {
                    window.Livewire.hook('morph.updated', () => {
                        this.updateFill();
                    });
                } catch (e) { /* morph hook unavailable - fill stays reactive via :style */ }
            }
        },
        updateFill() {
            const el = document.getElementById('weight');
            if (el) el.style.setProperty('--mw-fill', this.fillPercent() + '%');
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
        fillPercent() {
            const v = Math.min(100, Math.max(10, Number(this.localMachineWeight) || 10));
            return (((v - 10) / 90) * 100).toFixed(2);
        },
        bubblePosition() {
            const v = Math.min(100, Math.max(10, Number(this.localMachineWeight) || 10));
            const pct = ((v - 10) / 90) * 100;
            return Math.min(91, Math.max(9, pct)).toFixed(2);
        },
        applyFilters() {
            $wire.applyFilters(this.localCategory ?? '', this.localSortBy ?? '', this.localSubcategory ?? '', this.localConnection ?? '', '', String(this.localMachineWeight ?? ''), String(this.localPagination ?? ''));
        },
        clearAllFilters() {
            this.resetLocals();
            $wire.clearFilters();
        },
        resetLocals() {
            this.localCategory = '';
            this.localSortBy = 'price_low_high';
            this.localPagination = '25';
            this.localSubcategory = '';
            this.localConnection = '';
            this.localMachineWeight = 10;
            this.filterSubcategories();
            this.updateFill();
        },
    }" @filters-cleared.window="resetLocals()">
        <section class="w-full rounded-[22px] border border-gray-100 bg-white px-5 py-7 shadow-sm sm:px-8 lg:px-10">

            <h1 class="mb-5 text-2xl font-bold text-gray-900">
                Filter your search
            </h1>

            <!-- Top row -->
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-12 lg:items-end">

                <!-- Category -->
                <div class="lg:col-span-3">
                    <label for="category" class="block text-xs font-medium text-gray-500 mb-1.5">
                        Category
                    </label>
                    <div class="relative">
                        <select id="category" x-model="localCategory"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
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
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
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
                    <div class="flex overflow-hidden rounded-xl border border-slate-200 bg-white">
                        <div class="flex min-w-0 flex-1 items-center gap-3 px-4">
                            <svg class="h-6 w-6 shrink-0 text-slate-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-4.35-4.35m2.35-5.65a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                            </svg>
                            <input id="search" type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search product code or description"
                                class="min-w-0 flex-1 border-0 bg-transparent py-3.5 text-base text-slate-700 placeholder-slate-400 outline-none focus:ring-0" />
                        </div>
                             <x-ui.button type="button" label="Search"
                             class="m-1.5 rounded-xl bg-black px-7 py-3 text-base font-semibold text-white transition hover:bg-gray-800"
                             @click="applyFilters()"
                        wire:loading.attr="disabled" />
                    </div>
                </div>
            </div>

            <!-- Bottom row -->
            <div class="mt-7 grid grid-cols-1 gap-5 lg:grid-cols-12 lg:items-end">

                <!-- Subcategory -->
                <div class="lg:col-span-3">
                    <label for="subcategory" class="block text-xs font-medium text-gray-500 mb-1.5">
                        Subcategory
                    </label>
                    <div class="relative">
                        <select id="subcategory" x-model="localSubcategory"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            <option value="">All Subcategories</option>
                            @foreach ($subcategories ?? [] as $sub)
                                <option value="{{ $sub->slug }}" data-category-slug="{{ $sub->category?->slug }}">
                                    {{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Machine weight (Alpine-managed: skipped by Livewire morph) -->
                <div class="lg:col-span-3" wire:ignore>
                    <label for="weight" class="block text-xs font-medium text-gray-500 mb-1.5">
                        Machine weight
                    </label>

                    <div class="relative pt-1">
                        <span id="weightValue" x-text="localMachineWeight + ' ton'"
                            :style="{ left: bubblePosition() + '%' }"
                            class="mw-weight-bubble">
                            10 ton
                        </span>
                        <input id="weight" type="range" min="10" max="100" step="1"
                            x-model.number="localMachineWeight" aria-label="Machine weight in tons"
                            :style="{ '--mw-fill': fillPercent() + '%' }"
                            class="mw-slider mt-1 w-full cursor-pointer bg-transparent" />

                        <div class="mt-2 flex justify-between text-base text-slate-600">
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
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            @foreach ($sortOptions as $option)
                                <option value="{{ $option['value'] }}">{{ $option['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Per page -->
                <div class="lg:col-span-3">
                    <label for="perPage" class="block text-xs font-medium text-gray-500 mb-1.5">
                        Per page
                    </label>
                    <div class="relative">
                        <select id="perPage" x-model="localPagination"
                            class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-700 focus:border-bwtblue focus:ring-2 focus:ring-bwtblue/20 focus:outline-none transition-colors">
                            @foreach ($pageOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="mt-7 flex flex-wrap items-center gap-3">
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

            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
