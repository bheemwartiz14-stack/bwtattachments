<x-layouts.app>
    @php $isEdit = !empty($product); @endphp

    <x-slot:title>{{ $isEdit ? 'Edit Product' : 'Create Product' }} - Attachment Portal</x-slot:title>

    <x-breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
        ['label' => 'Products', 'url' => route('admin.products.index')],
        ['label' => $isEdit ? 'Edit Product' : 'Create Product']
    ]" />

    <div class="space-y-6">
        <x-ui.hero title="{{ $isEdit ? 'Edit Product' : 'Create Product' }}" subtitle="{{ $isEdit ? 'Update product details, media, and pricing' : 'Add a new product to the catalog' }}">
            <x-slot:icon>
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </x-slot:icon>
        </x-ui.hero>

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 dark:border-red-900/50 dark:bg-red-900/20">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 dark:text-red-300">Please fix {{ $errors->count() > 1 ? 'these ' . $errors->count() . ' errors' : 'this error' }}:</p>
                        <ul class="mt-1.5 list-disc space-y-1 text-sm text-red-700 dark:text-red-400 [&_li]:ml-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($isEdit) @method('PUT') @endif

            {{-- BASIC INFORMATION --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Basic Information</h2>
                </div>
                <div class="p-8 space-y-5">
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-forms.input name="product_title" label="Product Title" required
                            :value="$product->product_title ?? ''" placeholder="Enter product title" />
                        <x-forms.input name="product_code" label="Product Code" required
                            :value="$product->product_code ?? ''" placeholder="Enter product code" />
                        <x-forms.select name="category_id" label="Category" required
                            :options="$categories ?? []" :value="$product->category_id ?? ''"
                            placeholder="Select Category" />
                        <x-forms.select name="subcategory_id" label="Subcategory" required
                            :options="[]" placeholder="Select Category first"
                            disabled
                            data-selected="{{ old('subcategory_id', $product->subcategory_id ?? '') }}" />
                        <x-forms.select name="connection_id" label="Connection" required
                            :options="$connectionTypes ?? []" :value="$product->connection_id ?? ''"
                            placeholder="Select Connection" />
                    </div>
                    <x-forms.trix name="product_description" label="Product Description" required
                        :value="$product->product_description ?? ''" />
                    <x-forms.textarea name="internal_notes" label="Additional Info" rows="3"
                        :value="$product->internal_notes ?? ''" placeholder="Internal notes for admin reference"
                        hint="Admin only" />
                    <div class="grid gap-4 md:grid-cols-2">
                        <x-forms.currency name="ddp_price" label="DDP Price (EUR)"
                            :value="$product->ddp_price ?? ''" placeholder="0.00" />
                        <x-forms.toggle name="status" label="Product Status"
                            :checked="$product->status ?? true" description="Active" />
                    </div>
                </div>
            </div>

            {{-- MEDIA UPLOAD --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Media Upload</h2>
                </div>
                <div class="p-8 space-y-6">
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-700 dark:text-neutral-300">Product Feature Image</h3>
                        <x-forms.image-upload name="product_feature_image" label="Feature Image"
                            accept="image/jpeg,image/png,image/webp,image/gif"
                            :currentImageUrl="$isEdit ? $product->getFirstMediaUrl('images') : null"
                            hint="PNG, JPG, WebP or GIF (Max. 2MB)" />
                    </div>
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-700 dark:text-neutral-300">Product Gallery Images</h3>
                        @if($isEdit)
                            @php $galleryImages = $product->getMedia('gallery'); @endphp
                            @if($galleryImages->isNotEmpty())
                                <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
                                    @foreach($galleryImages as $media)
                                        <div class="group relative overflow-hidden rounded-lg border border-slate-200 dark:border-neutral-800">
                                            <img src="{{ $media->getUrl() }}" class="h-32 w-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                        <x-forms.dropzone name="product_gallery_images" accept="image/*" hint="PNG, JPG, WebP or GIF" />
                    </div>
                    <div>
                        <h3 class="mb-3 text-sm font-semibold text-slate-700 dark:text-neutral-300">Product PDF</h3>
                        <x-forms.input-file name="product_pdf" accept="application/pdf"
                            :currentFile="$isEdit && $product->getFirstMedia('pdfs') ? $product->getFirstMedia('pdfs')->file_name : null" />
                    </div>
                </div>
            </div>

            {{-- SPECIFICATIONS --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Specifications</h2>
                </div>
                <div class="p-8">
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @php
                            $specs = [
                                'weight' => ['type' => 'number', 'label' => 'Weight (kg)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00'],
                                'machine_class' => ['type' => 'number', 'label' => 'Machine Class/Weight (t)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00'],
                                'hinges' => ['type' => 'number', 'label' => 'Hinges', 'step' => '1', 'min' => '0', 'placeholder' => '0'],
                                'width' => ['type' => 'number', 'label' => 'Width (mm)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00', 'append' => 'mm'],
                                'volume' => ['type' => 'number', 'label' => 'Volume (L)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00'],
                                'cutting_edge_thickness' => ['type' => 'number', 'label' => 'Cutting Edge Thickness (mm)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00', 'append' => 'mm'],
                                'teeth' => ['type' => 'number', 'label' => 'Teeth', 'step' => '1', 'min' => '0', 'placeholder' => '0'],
                                'stick_width' => ['type' => 'number', 'label' => 'Stick Width (mm)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00', 'append' => 'mm'],
                                'pin_center' => ['type' => 'number', 'label' => 'Pin Center (mm)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00', 'append' => 'mm'],
                                'pin_hole' => ['type' => 'number', 'label' => 'Pin Hole (mm)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00', 'append' => 'mm'],
                                'thickness' => ['type' => 'number', 'label' => 'Thickness (mm)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00', 'append' => 'mm'],
                                'reach' => ['type' => 'number', 'label' => 'Reach (mm)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00', 'append' => 'mm'],
                                'machine_weight' => ['type' => 'number', 'label' => 'Machine Weight (t)', 'step' => '0.01', 'min' => '0', 'placeholder' => '0.00'],
                                'material' => ['type' => 'text', 'label' => 'Material', 'placeholder' => 'Enter material'],
                            ];
                        @endphp
                        @foreach($specs as $field => $config)
                            @if(($config['type'] ?? 'number') === 'number')
                                <x-forms.input name="{{ $field }}" :label="$config['label']" type="number"
                                    step="{{ $config['step'] }}" min="{{ $config['min'] ?? '0' }}"
                                    placeholder="{{ $config['placeholder'] ?? '0.00' }}"
                                    :append="$config['append'] ?? ''"
                                    :value="$product->$field ?? ''" />
                            @else
                                <x-forms.input name="{{ $field }}" :label="$config['label']"
                                    type="{{ $config['type'] }}"
                                    placeholder="{{ $config['placeholder'] ?? '' }}"
                                    :value="$product->$field ?? ''" />
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- WHOLESALE CLIENT PRICING --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-900" x-data="clientPricing(@js($wholesaleClients), {{ $isEdit ? json_encode($product->productPrices->map(fn($p) => ['user_id' => $p->user_id, 'price' => $p->price, 'user_name' => $p->user?->name ?? ''])) : '[]' }})">
                <div class="border-b border-slate-100 px-8 py-6 dark:border-neutral-800">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white">Wholesale Client Pricing</h2>
                </div>
                <div class="p-8">
                    <template x-if="prices.length === 0">
                        <p class="text-sm text-slate-400 dark:text-neutral-500">No client-specific prices set.</p>
                    </template>
                    <table x-show="prices.length > 0" class="mb-4 w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-neutral-800 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                <th class="pb-2 pr-4">Client</th>
                                <th class="pb-2 pr-4">Price (EUR)</th>
                                <th class="pb-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in prices" :key="index">
                                <tr class="border-b border-slate-100 dark:border-neutral-800">
                                    <td class="py-2 pr-4 text-sm text-slate-900 dark:text-white" x-text="item.user_name"></td>
                                    <td class="py-2 pr-4 text-sm font-medium text-slate-900 dark:text-white" x-text="'{{ config('app.currency_symbol') }}' + parseFloat(item.price).toFixed(2)"></td>
                                    <td class="py-2 text-right">
                                        <button type="button" @click="removePrice(index)" class="text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Remove</button>
                                    </td>
                                    <input type="hidden" :name="'product_prices[' + index + '][user_id]'" x-model="item.user_id">
                                    <input type="hidden" :name="'product_prices[' + index + '][price]'" x-model="item.price">
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <button type="button" @click="showModal = true"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Add Client Price
                    </button>
                </div>

                {{-- MODAL --}}
                <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" x-cloak>
                    <div @click.away="showModal = false" class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-neutral-950">
                        <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">Add Client Price</h3>
                        <div class="mb-4">
                            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-neutral-300">Select Wholesale Client</label>
                            <select x-model="form.user_id"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                <option value="">-- Select Client --</option>
                                <template x-for="client in clients" :key="client.id">
                                    <option :value="client.id" x-text="client.name" :disabled="selectedClientIds.includes(client.id)"></option>
                                </template>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-neutral-300">Price (EUR)</label>
                            <input type="number" step="0.01" min="0" x-model="form.price"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm dark:border-neutral-700 dark:bg-neutral-900 dark:text-white"
                                placeholder="0.00">
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="showModal = false"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</button>
                            <button type="button" @click="addPrice()"
                                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">Add</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-emerald-600 dark:hover:bg-emerald-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    {{ $isEdit ? 'Update Product' : 'Create Product' }}
                </button>
            </div>
        </form>
    </div>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/product.css') }}">
    <style>
    [x-cloak] { display: none !important; }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset('assets/js/product.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
    function clientPricing(clients, existingPrices) {
        return {
            clients: clients,
            prices: existingPrices || [],
            showModal: false,
            form: { user_id: '', price: '' },
            get selectedClientIds() {
                return this.prices.map(p => p.user_id);
            },
            addPrice() {
                if (!this.form.user_id || !this.form.price) return;
                var client = this.clients.find(c => c.id === this.form.user_id);
                this.prices.push({
                    user_id: this.form.user_id,
                    price: this.form.price,
                    user_name: client ? client.name : 'Unknown'
                });
                this.form = { user_id: '', price: '' };
                this.showModal = false;
            },
            removePrice(index) {
                this.prices.splice(index, 1);
            }
        };
    }
    </script>
    @endpush
</x-layouts.app>
