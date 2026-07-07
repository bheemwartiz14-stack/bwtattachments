<x-layouts.public>
    <x-slot:title>{{ $product->description ?? $product->title ?? 'Product Detail' }} - BWT</x-slot:title>

    <div class="bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('public.products.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-emerald-600 hover:text-emerald-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Products
                </a>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-5">
                <div class="space-y-6 lg:col-span-3">
                    @php
                        $featureImage = $product->getFirstMediaUrl('images', 'large');
                        $galleryMedia = $product->getMedia('gallery');
                        $allImages = [];
                        if ($featureImage) {
                            $allImages[] = ['url' => $featureImage, 'alt' => $product->product_title ?? $product->description ?? ''];
                        }
                        foreach ($galleryMedia as $media) {
                            $allImages[] = ['url' => $media->getUrl('large'), 'thumb' => $media->getUrl(), 'alt' => ''];
                        }
                    @endphp
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                         x-data="{ current: 0, images: {{ json_encode($allImages) }} }">
                        <div class="relative aspect-[15/10] bg-slate-100">
                            <template x-if="images.length">
                                <template x-for="(img, i) in images" :key="i">
                                    <img x-show="current === i"
                                         :src="img.url"
                                         :alt="img.alt"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="absolute inset-0 w-full h-full object-contain p-4">
                                </template>
                            </template>
                            <template x-if="!images.length">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="mx-auto h-20 w-20 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-2 text-sm text-slate-400">Product Image</p>
                                    </div>
                                </div>
                            </template>

                            <template x-if="images.length > 1">
                                <div class="absolute inset-0 z-10">
                                    <button @click="current = current > 0 ? current - 1 : images.length - 1"
                                            class="absolute left-3 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/80 shadow-md hover:bg-white transition-all">
                                        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                                    </button>
                                    <button @click="current = current < images.length - 1 ? current + 1 : 0"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-white/80 shadow-md hover:bg-white transition-all">
                                        <svg class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                    </button>
                                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5">
                                        <template x-for="(img, i) in images" :key="i">
                                            <button @click="current = i"
                                                    :class="current === i ? 'bg-emerald-500 w-6' : 'bg-white/60 hover:bg-white w-2'"
                                                    class="h-2 rounded-full transition-all"></button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <template x-if="images.length > 1">
                            <div class="flex gap-2 overflow-x-auto px-4 pb-4 pt-2 scrollbar-thin">
                                <template x-for="(img, i) in images" :key="i">
                                    <button @click="current = i"
                                            :class="current === i ? 'ring-2 ring-emerald-500 opacity-100' : 'opacity-50 hover:opacity-80'"
                                            class="w-20 aspect-[15/10] flex-shrink-0 rounded-lg overflow-hidden transition-all cursor-pointer bg-slate-100">
                                        <img :src="img.thumb || img.url" alt="" class="w-full h-full object-contain">
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-4 text-2xl font-bold tracking-tight text-slate-950">Technical Specifications</h2>
                        @php
                            $specs = [
                                'weight' => ['label' => 'Weight (kg)', 'unit' => 'kg'],
                                'width' => ['label' => 'Width', 'unit' => 'mm'],
                                'volume' => ['label' => 'Volume (m3)', 'unit' => 'm³'],
                                'machine_class' => ['label' => 'Machine class (t)', 'unit' => 't'],
                                'cutting_edge_thickness' => ['label' => 'Cutting edge thickness (mm)', 'unit' => 'mm'],
                                'teeth' => ['label' => 'Teeth', 'unit' => null],
                                'pin_hole' => ['label' => 'Pin Hole (mm)', 'unit' => 'mm'],
                                'pin_center' => ['label' => 'Pin center to Pin center (mm)', 'unit' => 'mm'],
                                'stick_width' => ['label' => 'Stick Width', 'unit' => 'mm'],
                            ];
                        @endphp
                        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                            @foreach($specs as $field => $config)
                                @php
                                    $value = $product->$field;
                                    if (in_array($field, ['weight', 'width']) && $value !== null) {
                                        $value = rtrim(rtrim($value, '0'), '.');
                                    }
                                @endphp
                                <div class="rounded-lg bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">{{ $config['label'] }}</p>
                                    <p class="mt-0.5 text-sm font-semibold text-slate-950">
                                        @if($value !== null && $value !== '')
                                            {{ $value }}
                                            @if($config['unit'])
                                                <span class="text-xs font-normal text-slate-400">{{ $config['unit'] }}</span>
                                            @endif
                                        @else
                                            <span class="text-sm font-normal text-slate-300">""</span>
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="mb-3 text-2xl font-bold tracking-tight text-slate-950">Description</h2>
                        <p class="text-sm leading-relaxed text-slate-700 break-words">
                            {{ $product->long_description ?? $product->description ?? $product->product_description ?? $product->title ?? 'No description available.' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-6 lg:col-span-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="font-mono text-xs font-medium text-emerald-600">{{ $product->product_code ?? $product->code ?? 'CODE-001' }}</p>
                        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">{{ $product->description ?? $product->title ?? 'Product Name' }}</h1>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @if($product->category ?? false)
                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">{{ $product->category->name ?? $product->category }}</span>
                            @endif
                            @if($product->subcategory ?? false)
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">{{ $product->subcategory }}</span>
                            @endif
                            @if($product->connection_type ?? $product->connectionType ?? false)
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700">{{ $product->connection_type ?? $product->connectionType }}</span>
                            @endif
                        </div>

                        <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-6 text-center">
                            <p class="text-sm font-medium text-emerald-700">Wholesale pricing available</p>
                            <p class="mt-2 text-sm text-emerald-600">Log in to your account to view pricing, request quotations, and download product documents.</p>
                            <a href="{{ route('login') }}" class="mt-4 inline-flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Login to View Pricing
                            </a>
                        </div>

                        @auth
                            @if(auth()->user()->isAdmin())
                                <div class="mt-6 border-t border-slate-100 pt-6">
                                    <div class="mb-3 flex items-center gap-2">
                                        <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <h3 class="text-sm font-semibold text-slate-950">Internal Notes</h3>
                                    </div>
                                    <textarea class="block w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm placeholder-slate-400 shadow-sm transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500" rows="3" placeholder="Add internal notes..."></textarea>
                                    <button class="mt-2 inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-medium text-slate-700 shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Save Note</button>
                                </div>
                            @endif
                        @endauth
                    </div>

                    @if(($product->documents ?? $product->files ?? false) || true)
                        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                            <h3 class="mb-3 text-sm font-semibold text-slate-950">Related Documents</h3>
                            <div class="space-y-2">
                                <a href="#" class="group flex items-center gap-3 rounded-lg p-3 transition-colors hover:bg-slate-50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50">
                                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-slate-950 transition-colors group-hover:text-emerald-600">Datasheet_{{ $product->product_code ?? $product->code ?? 'product' }}.pdf</p>
                                        <p class="text-xs text-slate-400">PDF &middot; 2.4 MB</p>
                                    </div>
                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
