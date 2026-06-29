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
                        <x-forms.input name="product_title" label="Product Name" required
                            :value="$product->product_title ?? ''" placeholder="Enter Product Name" />
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
                        <x-forms.input name="drawing_number" label="Drawing Number" required
                            :value="$product->drawing_number ?? ''" placeholder="Enter drawing number" />
                    </div>
                    <x-forms.textarea name="product_description" label="Product Description" required rows="5"
                        :value="$product->product_description ?? ''" placeholder="Enter product description" />
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
                    <x-forms.image-dropzone name="product_feature_image"
                        :existingImageUrl="$isEdit ? $product->getFirstMediaUrl('images') : null"
                        :existingImageId="$isEdit && $product->getFirstMedia('images') ? $product->getFirstMedia('images')->id : null"
                        label="Product Feature Image"


                        />
                    <x-forms.gallery-dropzone
                        :existingImages="$isEdit ? $product->getMedia('gallery')->map(fn($m) => ['url' => $m->getUrl(), 'name' => $m->file_name, 'id' => $m->id])->toArray() : []"
                        name="product_gallery_images"
                        />
                    <x-forms.pdf-upload
                        :existingFile="$isEdit && $product->getFirstMedia('pdfs') ? $product->getFirstMedia('pdfs')->file_name : null"
                        :existingUrl="$isEdit && $product->getFirstMedia('pdfs') ? $product->getFirstMedia('pdfs')->getUrl() : null"
                        :existingSize="$isEdit && $product->getFirstMedia('pdfs') ? $product->getFirstMedia('pdfs')->size : null"
                        :existingFileId="$isEdit && $product->getFirstMedia('pdfs') ? $product->getFirstMedia('pdfs')->id : null"
                        name="product_pdf"
                        />
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
                                'weight' => ['label' => 'Weight (kg)', 'placeholder' => 'e.g. 1.5 or 1.5-2.0'],
                                'width' => ['label' => 'Width (mm)', 'placeholder' => 'e.g. 200 or 200-250'],
                                'volume' => ['label' => 'Volume (m3)', 'placeholder' => 'e.g. 0.5 or 0.5-0.8'],
                                'machine_class' => ['label' => 'Machine class (t)', 'placeholder' => 'e.g. 20 or 20-30'],
                                'cutting_edge_thickness' => ['label' => 'Cutting Edge Thickness (mm)', 'placeholder' => 'e.g. 10 or 10-15'],
                                'teeth' => ['label' => 'Teeth', 'placeholder' => 'e.g. 4 or 4-6'],
                                'pin_hole' => ['label' => 'Pin Hole (mm)', 'placeholder' => 'e.g. 30 or 30-40'],
                                'pin_center' => ['label' => 'Pin center to Pin center (mm)', 'placeholder' => 'e.g. 50 or 50-60'],
                                'stick_width' => ['label' => 'Stick Width (mm)', 'placeholder' => 'e.g. 80 or 80-100'],
                            ];
                        @endphp
                        @foreach($specs as $field => $config)
                            <x-forms.input name="{{ $field }}" :label="$config['label']"
                                placeholder="{{ $config['placeholder'] }}"
                                :value="$product->$field ?? ''" />
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="flex items-center justify-end gap-3" x-data="{ submitting: false }">
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-300 dark:hover:bg-neutral-800">Cancel</a>
                <x-ui.button type="submit" :loading="false" x-bind:loading="submitting" x-on:click="setTimeout(() => submitting = true, 50)" :label="$isEdit ? 'Update Product' : 'Create Product'">
                    <x-slot:icon>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </x-slot:icon>
                </x-ui.button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script src="{{ asset('assets/js/product.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
</x-layouts.app>
