<x-layouts.public>
    <x-slot:title>{{ $product->product_title ?? 'Product Detail' }} - BWT</x-slot:title>

    <main class="min-h-screen bg-gradient-to-b from-slate-50 to-white">
        <div class="max-w-[1700px] mx-auto px-6 lg:px-8 py-6">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
                <a href="{{ route('public.home.index') }}" wire:navigate
                    class="hover:text-bwtblue transition-colors no-underline">Products</a>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
                @if ($product->category)
                    <a href="{{ route('public.categories.show', $product->category) }}" wire:navigate
                        class="hover:text-bwtblue transition-colors no-underline">{{ $product->category->name }}</a>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                @endif
                <span
                    class="text-slate-900 font-medium truncate">{{ $product->product_title ?? $product->product_code }}</span>
            </nav>

            {{-- Main Content --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 xl:gap-14">

                {{-- Left: Gallery Slider --}}
                @php
                    $featureMedia = $product->getFirstMedia('images');
                    $galleryMedia = $product->getMedia('gallery');
                    $allImages = collect();
                    if ($featureMedia) {
                        $allImages->push($featureMedia);
                    }
                    foreach ($galleryMedia as $m) {
                        $allImages->push($m);
                    }
                    $isFavorited =
                        auth()->check() &&
                        $product
                            ->favoritedByUsers()
                            ->where('user_id', auth()->id())
                            ->exists();
                @endphp
                <div class="space-y-4" id="productGallery">
                    <div class="relative group overflow-hidden rounded-2xl bg-slate-50 aspect-[3/2]">
                        @foreach ($allImages as $i => $img)
                            <img src="{{ $img->getUrl() }}" alt="{{ $product->product_title }}"
                                data-index="{{ $i }}"
                                class="gallery-image absolute inset-0 w-full h-full object-contain @if ($i > 0) hidden @endif">
                        @endforeach

                        <button type="button"
                            class="gallery-expand absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full p-2 shadow-sm md:opacity-0 md:group-hover:opacity-100 transition-all duration-200 hover:bg-white">
                            <svg class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </button>

                        @if ($allImages->count() > 1)
                            <div
                                class="absolute inset-y-0 left-0 right-0 flex items-center justify-between px-3 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                <button type="button"
                                    class="gallery-prev bg-white/90 backdrop-blur-sm rounded-full p-2 shadow-sm hover:bg-white transition-all">
                                    <svg class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <button type="button"
                                    class="gallery-next bg-white/90 backdrop-blur-sm rounded-full p-2 shadow-sm hover:bg-white transition-all">
                                    <svg class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>

                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                                @foreach ($allImages as $i => $img)
                                    <button type="button"
                                        class="gallery-dot h-2 rounded-full transition-all duration-300 @if ($i === 0) bg-white w-5 @else bg-white/50 w-2 @endif"
                                        data-index="{{ $i }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Thumbnail Strip --}}
                    @if ($allImages->count() > 1)
                        <div class="flex gap-3 overflow-x-auto pb-1">
                            @foreach ($allImages as $i => $img)
                                <button type="button" onclick="showGalleryImage({{ $i }})"
                                    class="gallery-thumb flex-shrink-0 w-28 h-[4.66rem] sm:w-32 sm:h-[5.33rem] rounded-lg overflow-hidden transition-all duration-200 hover:shadow-md @if ($i === 0) ring-2 ring-bwtblue @else ring-1 ring-slate-200 hover:ring-slate-300 @endif">
                                    <img src="{{ $img->getUrl() }}" alt="" class="w-full h-full object-contain bg-slate-50">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Right: Info --}}
                <div class="space-y-6">
                    {{-- Code & Title --}}
                    <div>
                        <span
                            class="inline-block font-mono text-xs font-semibold tracking-widest text-bwtblue uppercase bg-blue-50 px-3 py-1 rounded-full">{{ $product->product_code ?? 'N/A' }}</span>
                        <h1 class="text-3xl lg:text-4xl font-bold text-slate-900 mt-3 leading-tight">
                            {{ $product->product_title ?? 'Product' }}</h1>
                    </div>

                    {{-- Tags --}}
                    @if ($product->category || $product->subcategory || $product->connection)
                        <div class="flex flex-wrap gap-2">
                            @if ($product->category)
                                <span
                                    class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 text-xs font-medium px-3 py-1.5 rounded-full border border-emerald-200">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    {{ $product->category->name }}
                                </span>
                            @endif

                            @if ($product->subcategory)
                                <span
                                    class="inline-flex items-center gap-1.5 bg-blue-50 text-bwtblue text-xs font-medium px-3 py-1.5 rounded-full border border-blue-200">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    {{ $product->subcategory->name }}
                                </span>
                            @endif
                            @if ($product->connection)
                                <span
                                    class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 text-xs font-medium px-3 py-1.5 rounded-full border border-amber-200">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.63a4.5 4.5 0 00-6.364-6.364L4.5 8.688a4.5 4.5 0 001.242 7.244" />
                                    </svg>
                                    {{ $product->connection->name }}
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Description --}}
                    @if ($product->product_description)
                        @php
                            $description = trim($product->product_description);

                            // Detect HTML tags
                            $isHtml = $description !== strip_tags($description);
                        @endphp

                        <div class="product-description prose max-w-none">
                            @if ($isHtml)
                                <div class="mt-1 text-sm leading-relaxed  break-words whitespace-pre-wrap ">
                                    {!! Str::cleanHtml($product->product_description) !!}</dd>
                                @else
                                    {{-- Plain Text --}}
                                    {!! nl2br(e($description)) !!}
                            @endif
                        </div>
                    @endif
                    <div class="relative bg-white rounded-2xl">
                        @auth
                            <x-product.favorite-button :product="$product" :is-favorited="$isFavorited" variant="absolute" />
                        @endauth

                    </div>

                    {{-- Specs Grid --}}
                    @php
                        $specs = [
                            'Weight' => $product->weight
                                ? [
                                    'value' => $product->weight . ' kg',
                                    'icon' =>
                                        'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
                                ]
                                : null,
                            'Width' => $product->width
                                ? [
                                    'value' => $product->width . ' mm',
                                    'icon' =>
                                        'M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4',
                                ]
                                : null,
                            'Volume' => $product->volume
                                ? [
                                    'value' => $product->volume . ' m³',
                                    'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                                ]
                                : null,
                            'Machine Class' => $product->machine_class
                                ? [
                                    'value' => $product->machine_class . ' t',
                                    'icon' =>
                                        'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
                                ]
                                : null,
                            'Cutting edge thickness (mm)' => $product->cutting_edge_thickness
                                ? [
                                    'value' => $product->cutting_edge_thickness . ' mm',
                                    'icon' => 'M6 6h12M6 12h12M6 18h12',
                                ]
                                : null,
                            'Teeth' => $product->teeth
                                ? [
                                    'value' => $product->teeth,
                                    'icon' =>
                                        'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z',
                                ]
                                : null,
                            'Pin Hole' => $product->pin_hole
                                ? [
                                    'value' => $product->pin_hole . ' mm',
                                    'icon' =>
                                        'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z',
                                ]
                                : null,
                            'Pin Center' => $product->pin_center
                                ? [
                                    'value' => $product->pin_center . ' mm',
                                    'icon' =>
                                        'M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z',
                                ]
                                : null,
                            'Stick Width' => $product->stick_width
                                ? ['value' => $product->stick_width . ' mm', 'icon' => 'M6 18L18 6M6 6l12 12']
                                : null,
                        ];
                        $specs = array_filter($specs);
                    @endphp

                    @if (count($specs) > 0)
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900 mb-3">Technical Specifications</h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach ($specs as $label => $spec)
                                    <div
                                        class="bg-slate-50 rounded-xl border border-slate-200 p-4 transition-all duration-200 hover:border-bwtblue/30 hover:shadow-sm hover:bg-white">
                                        <div class="flex items-center gap-2 mb-1.5">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="{{ $spec['icon'] }}" />
                                            </svg>
                                            <span
                                                class="text-xs font-medium text-slate-500 uppercase tracking-wider">{{ $label }}</span>
                                        </div>
                                        <p class="text-lg font-bold text-slate-900">{{ $spec['value'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Pricing & CTA --}}
                    <div class="mt-6 bg-blue-50 rounded-md p-5">
                        <h3 class="font-semibold text-gray-900 text-sm mb-2">
                            @auth Wholesale Client Area
                            @else
                            Wholesale Client Area "" Login Required @endauth
                        </h3>
                        @auth
                            @php
                                $userPrice = $product->productPrices->firstWhere('user_id', auth()->id());
                            @endphp
                            @if ($userPrice || $product->ddp_price)
                                <div class="text-2xl font-bold text-green-600 my-3">
                                    {{ config('app.currency_symbol') }}
                                    {{ number_format($userPrice->final_price ?? $product->ddp_price, 2) }}
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500 mb-3">
                                Log in to view wholesale pricing, request quotations, and download product documents.
                            </p>
                        @endauth

                        <div class="flex flex-wrap gap-3 mt-4">
                            @auth
                                @role('Wholesale Client')
                                    @if ($product->getFirstMedia('pdfs'))
                                        <a href="{{ route('public.products.pdf', $product) }}" wire:navigate target="_blank"
                                            class="inline-block bg-teal-500 hover:bg-teal-600 text-white text-sm font-medium px-5 py-2.5 rounded no-underline transition-colors">
                                            Download Technical PDF
                                        </a>
                                    @endif
                                @endrole
                                @role('Wholesale Client|Reseller')
                                    <a class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2.5 rounded no-underline transition-colors"
                                        href="{{ auth()->user()->hasRole('Wholesale Client')
                                            ? route('client.quotations.create')
                                            : route('reseller.quotations.create') }}"
                                        wire:navigate>
                                        Add To Quotation
                                    </a>
                                @endrole
                            @else
                                <a href="{{ route('login') }}" wire:navigate
                                    class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2.5 rounded no-underline transition-colors">
                                    Login to View Pricing
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Lightbox Modal --}}
    <div id="lightbox"
        class="fixed inset-0 z-50 bg-black/90 backdrop-blur-sm flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300"
        onclick="if(event.target === this) galleryCloseLightbox()">
        <button onclick="galleryCloseLightbox()"
            class="absolute top-6 right-6 text-white/70 hover:text-white transition-colors">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="relative flex items-center">
            <button onclick="galleryPrevLightbox()"
                class="absolute -left-16 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition-colors">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <img id="lightboxImage" src="" alt=""
                class="max-w-[90vw] max-h-[90vh] object-contain rounded-2xl">
            <button onclick="galleryNextLightbox()"
                class="absolute -right-16 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition-colors">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

</x-layouts.public>
