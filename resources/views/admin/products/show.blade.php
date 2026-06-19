<x-layouts.app>
    <x-slot:title>{{ $product->product_description ?? $product->name }} - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Admin', 'url' => route('admin.dashboard')], ['label' => 'Products', 'url' => route('admin.products.index')], ['label' => $product->product_code]]" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950 dark:text-slate-100">{{ $product->product_description ?? $product->name }}</h1>
                <p class="text-sm text-gray-700 mt-1">
                    Code: <span class="font-mono text-emerald-600 dark:text-emerald-400">{{ $product->product_code }}</span>
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-rose-50 px-4 py-2 text-sm font-medium text-rose-700 transition-colors hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:bg-rose-900/30 dark:text-rose-300 dark:hover:bg-rose-900/50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Edit
                </a>
                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-100 dark:hover:bg-slate-700">
                    Back
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Product Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Category</dt>
                            <dd class="mt-1 text-sm text-black dark:text-gray-100">{{ $product->category->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Subcategory</dt>
                            <dd class="mt-1 text-sm text-black dark:text-gray-100">{{ $product->subcategory->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Connection Type</dt>
                            <dd class="mt-1 text-sm text-black dark:text-gray-100">{{ $product->connection->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Status</dt>
                            <dd class="mt-1">
                                @if($product->status === 'published')
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">Published</span>
                                @elseif($product->status === 'draft')
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900/50 dark:text-amber-300">Draft</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-300">Hidden</span>
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">DDP Price</dt>
                            <dd class="mt-1 text-sm font-semibold text-black dark:text-gray-100">${{ number_format($product->ddp_price, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Currency</dt>
                            <dd class="mt-1 text-sm text-black dark:text-gray-100">{{ $product->currency ?? 'USD' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Featured</dt>
                            <dd class="mt-1 text-sm text-black dark:text-gray-100">{{ $product->featured ? 'Yes' : 'No' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Created</dt>
                            <dd class="mt-1 text-sm text-black dark:text-gray-100">{{ $product->created_at->format('M d, Y') }}</dd>
                        </div>
                        @if($product->product_description)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Description</dt>
                                <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $product->product_description }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Specifications</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4">
                        @if($product->weight_kg)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-4">
                                <dt class="text-xs font-medium text-gray-400 dark:text-gray-500">Weight</dt>
                                <dd class="mt-1 text-lg font-semibold text-black dark:text-gray-100">{{ $product->weight_kg }} <span class="text-sm font-normal text-gray-400">kg</span></dd>
                            </div>
                        @endif
                        @if($product->width_mm)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-4">
                                <dt class="text-xs font-medium text-gray-400 dark:text-gray-500">Width</dt>
                                <dd class="mt-1 text-lg font-semibold text-black dark:text-gray-100">{{ $product->width_mm }} <span class="text-sm font-normal text-gray-400">mm</span></dd>
                            </div>
                        @endif
                        @if($product->volume_m3)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-4">
                                <dt class="text-xs font-medium text-gray-400 dark:text-gray-500">Volume</dt>
                                <dd class="mt-1 text-lg font-semibold text-black dark:text-gray-100">{{ $product->volume_m3 }} <span class="text-sm font-normal text-gray-400">m³</span></dd>
                            </div>
                        @endif
                        @if($product->num_teeth)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-4">
                                <dt class="text-xs font-medium text-gray-400 dark:text-gray-500">Teeth</dt>
                                <dd class="mt-1 text-lg font-semibold text-black dark:text-gray-100">{{ $product->num_teeth }}</dd>
                            </div>
                        @endif
                        @if($product->pin_hole_diameter_mm)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-4">
                                <dt class="text-xs font-medium text-gray-400 dark:text-gray-500">Pin Hole Ø</dt>
                                <dd class="mt-1 text-lg font-semibold text-black dark:text-gray-100">{{ $product->pin_hole_diameter_mm }} <span class="text-sm font-normal text-gray-400">mm</span></dd>
                            </div>
                        @endif
                        @if($product->machine_class_t)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-4">
                                <dt class="text-xs font-medium text-gray-400 dark:text-gray-500">Machine Class</dt>
                                <dd class="mt-1 text-lg font-semibold text-black dark:text-gray-100">{{ $product->machine_class_t }} <span class="text-sm font-normal text-gray-400">T</span></dd>
                            </div>
                        @endif
                        @if($product->material)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-4">
                                <dt class="text-xs font-medium text-gray-400 dark:text-gray-500">Material</dt>
                                <dd class="mt-1 text-lg font-semibold text-black dark:text-gray-100">{{ $product->material }}</dd>
                            </div>
                        @endif
                        @if($product->thickness_mm)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-4">
                                <dt class="text-xs font-medium text-gray-400 dark:text-gray-500">Thickness</dt>
                                <dd class="mt-1 text-lg font-semibold text-black dark:text-gray-100">{{ $product->thickness_mm }} <span class="text-sm font-normal text-gray-400">mm</span></dd>
                            </div>
                        @endif
                        @if($product->reach_mm)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/50 p-4">
                                <dt class="text-xs font-medium text-gray-400 dark:text-gray-500">Reach</dt>
                                <dd class="mt-1 text-lg font-semibold text-black dark:text-gray-100">{{ $product->reach_mm }} <span class="text-sm font-normal text-gray-400">mm</span></dd>
                            </div>
                        @endif
                    </dl>
                </div>

                @if($product->notes)
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Internal Notes</h2>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $product->notes }}</p>
                        <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">These notes are only visible to administrators.</p>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Images</h2>
                    @if($product->images->count())
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($product->images as $image)
                                <a href="{{ Storage::url($image->path) }}" target="_blank" class="block rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 hover:ring-2 hover:ring-emerald-500 transition-all">
                                    <img src="{{ Storage::url($image->path) }}" alt="" class="w-full h-28 object-cover">
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <p class="mt-2 text-sm text-gray-400">No images uploaded.</p>
                        </div>
                    @endif
                </div>

                @if($product->pdf_file)
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                        <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Datasheet</h2>
                        <a href="{{ Storage::url($product->pdf_file) }}" target="_blank" class="inline-flex items-center gap-3 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 transition-colors w-full">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-rose-100 dark:bg-rose-900/50">
                                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-rose-700 dark:text-rose-300">Download PDF</p>
                                <p class="text-xs text-gray-400">Product Datasheet</p>
                            </div>
                        </a>
                    </div>
                @endif

                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-slate-100 mb-4">Quick Actions</h2>
                    <div class="space-y-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-rose-50 dark:hover:bg-slate-800/50 transition-colors text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Edit Product
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-3 p-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors text-sm text-red-600 dark:text-red-400 w-full">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
