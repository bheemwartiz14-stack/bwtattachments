<x-layouts.app>
    <x-slot:title>{{ isset($product) ? 'Edit Product' : 'Add Product' }} - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => route('admin.dashboard')], ['label' => 'Products', 'url' => route('admin.products.index')], ['label' => isset($product) ? 'Edit' : 'New Product']]" />

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">{{ isset($product) ? 'Edit Product' : 'New Product' }}</h1>
                <p class="text-sm text-gray-700 mt-1">Fill in the details below</p>
            </div>
        </div>

        @if($errors->any())
            <div class="rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/30 dark:border-red-800">
                <div class="flex items-center gap-2 text-sm text-red-800 dark:text-red-300 mb-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="font-medium">Please fix the following errors:</span>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-300 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @isset($product)
                @method('PUT')
            @endisset

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 space-y-5">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Basic Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="product_description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Product Description <span class="text-red-500">*</span></label>
                        <input type="text" id="product_description" name="product_description" value="{{ old('product_description', $product->product_description ?? '') }}" placeholder="e.g. Bucket GP-12" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500 @error('product_description') border-red-300 @enderror">
                        @error('product_description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="product_code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Product Code <span class="text-red-500">*</span></label>
                        <input type="text" id="product_code" name="product_code" value="{{ old('product_code', $product->product_code ?? '') }}" placeholder="e.g. BKT-GP-12" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500 @error('product_code') border-red-300 @enderror">
                        @error('product_code')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Category <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 @error('category_id') border-red-300 @enderror">
                            <option value="">Select category</option>
                            @foreach($catagories ?? [] as $cat)
                                <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="subcategory_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Subcategory</label>
                        <select id="subcategory_id" name="subcategory_id" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                            <option value="">Select subcategory</option>
                            @foreach($subcategories ?? [] as $sub)
                                <option value="{{ $sub->id }}" @selected(old('subcategory_id', $product->subcategory_id ?? '') == $sub->id)>{{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="connection_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Connection Type</label>
                        <select id="connection_id" name="connection_id" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                            <option value="">Select connection</option>
                            @foreach($connections ?? [] as $conn)
                                <option value="{{ $conn->id }}" @selected(old('connection_id', $product->connection_id ?? '') == $conn->id)>{{ $conn->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 space-y-5">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Specifications</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="weight_kg" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Weight (kg)</label>
                        <input type="number" step="0.01" id="weight_kg" name="weight_kg" value="{{ old('weight_kg', $product->weight_kg ?? '') }}" placeholder="1200" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    </div>
                    <div>
                        <label for="width_mm" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Width (mm)</label>
                        <input type="number" step="0.01" id="width_mm" name="width_mm" value="{{ old('width_mm', $product->width_mm ?? '') }}" placeholder="1800" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    </div>
                    <div>
                        <label for="volume_m3" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Volume (m³)</label>
                        <input type="number" step="0.01" id="volume_m3" name="volume_m3" value="{{ old('volume_m3', $product->volume_m3 ?? '') }}" placeholder="1.5" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    </div>
                    <div>
                        <label for="num_teeth" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Number of Teeth</label>
                        <input type="number" id="num_teeth" name="num_teeth" value="{{ old('num_teeth', $product->num_teeth ?? '') }}" placeholder="5" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    </div>
                    <div>
                        <label for="pin_hole_diameter_mm" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Pin Hole Ø (mm)</label>
                        <input type="number" step="0.01" id="pin_hole_diameter_mm" name="pin_hole_diameter_mm" value="{{ old('pin_hole_diameter_mm', $product->pin_hole_diameter_mm ?? '') }}" placeholder="80" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    </div>
                    <div>
                        <label for="machine_class_t" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Machine Class (T)</label>
                        <input type="text" id="machine_class_t" name="machine_class_t" value="{{ old('machine_class_t', $product->machine_class_t ?? '') }}" placeholder="10-15" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    </div>
                    <div>
                        <label for="material" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Material</label>
                        <input type="text" id="material" name="material" value="{{ old('material', $product->material ?? '') }}" placeholder="Hardox 400" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    </div>
                    <div>
                        <label for="thickness_mm" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Thickness (mm)</label>
                        <input type="number" step="0.01" id="thickness_mm" name="thickness_mm" value="{{ old('thickness_mm', $product->thickness_mm ?? '') }}" placeholder="25" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    </div>
                    <div>
                        <label for="reach_mm" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Reach (mm)</label>
                        <input type="number" step="0.01" id="reach_mm" name="reach_mm" value="{{ old('reach_mm', $product->reach_mm ?? '') }}" placeholder="1200" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 space-y-5">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Pricing</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="ddp_price" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">DDP Price ($) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 text-sm">$</span>
                            <input type="number" step="0.01" id="ddp_price" name="ddp_price" value="{{ old('ddp_price', $product->ddp_price ?? '') }}" placeholder="4500.00" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pl-7 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500 @error('ddp_price') border-red-300 @enderror">
                        </div>
                        @error('ddp_price')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="currency" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Currency</label>
                        <select id="currency" name="currency" class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100">
                            <option value="USD" @selected(old('currency', $product->currency ?? 'USD') === 'USD')>USD</option>
                            <option value="EUR" @selected(old('currency', $product->currency ?? '') === 'EUR')>EUR</option>
                            <option value="GBP" @selected(old('currency', $product->currency ?? '') === 'GBP')>GBP</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Status</label>
                        <div class="flex items-center gap-6 mt-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="published" class="w-4 h-4 text-emerald-600 border-slate-100 dark:border-slate-700 dark:border-slate-600 focus:ring-emerald-500" @checked(old('status', $product->status ?? 'published') === 'published')>
                                <span class="text-sm text-black dark:text-gray-100">Published</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="draft" class="w-4 h-4 text-emerald-600 border-slate-100 dark:border-slate-700 dark:border-slate-600 focus:ring-emerald-500" @checked(old('status', $product->status ?? '') === 'draft')>
                                <span class="text-sm text-black dark:text-gray-100">Draft</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="status" value="hidden" class="w-4 h-4 text-emerald-600 border-slate-100 dark:border-slate-700 dark:border-slate-600 focus:ring-emerald-500" @checked(old('status', $product->status ?? '') === 'hidden')>
                                <span class="text-sm text-black dark:text-gray-100">Hidden</span>
                            </label>
                        </div>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 space-y-5">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Media Upload</h2>
                <div>
                    <label for="images" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Product Images</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 dark:file:bg-rose-900/30 dark:file:text-rose-300 dark:hover:file:bg-rose-900/50">
                    @error('images.*')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @if(isset($product) && $product->images->count())
                        <div class="mt-3 grid grid-cols-4 sm:grid-cols-6 gap-2">
                            @foreach($product->images as $image)
                                <div class="relative group">
                                    <img src="{{ Storage::url($image->path) }}" alt="" class="w-full h-20 object-cover rounded-lg border border-slate-200 dark:border-slate-700">
                                    <button type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity" onclick="this.closest('div').remove()">&times;</button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div>
                    <label for="pdf_file" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Product PDF</label>
                    <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-rose-50 file:text-rose-700 hover:file:bg-rose-100 dark:file:bg-rose-900/30 dark:file:text-rose-300 dark:hover:file:bg-rose-900/50">
                    @error('pdf_file')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    @if(isset($product) && $product->pdf_file)
                        <p class="mt-2 text-xs text-gray-500">Current PDF: <a href="{{ Storage::url($product->pdf_file) }}" target="_blank" class="text-rose-600 hover:text-rose-700">View file</a></p>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900 space-y-5">
                <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100">Internal Notes</h2>
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Notes (Admin only)</label>
                    <textarea id="notes" name="notes" rows="5" placeholder="Supplier info, lead times, MOQ, internal remarks..." class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:placeholder-slate-500">{{ old('notes', $product->notes ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">These notes are only visible to administrators.</p>
                </div>
                <div class="flex items-center gap-3">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-0">Featured Product</label>
                    <label for="featured" class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="featured" name="featured" value="1" class="sr-only peer" @checked(old('featured', $product->featured ?? false))>
                        <div class="w-9 h-5 bg-slate-300 dark:bg-slate-600 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    {{ isset($product) ? 'Update Product' : 'Create Product' }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
