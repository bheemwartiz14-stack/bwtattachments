<x-layouts.app>
    <x-slot:title>{{ $product->product_title }} - BWT</x-slot:title>
    <x-breadcrumb :items="[
        ['label' => 'Admin', 'url' => route('admin.dashboard')],
        ['label' => 'Products', 'url' => route('admin.products.index')],
        ['label' => $product->product_code]
    ]" />


    @if(session('success'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6">
        {{-- HERO --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 shadow-2xl">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMyI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-40"></div>
            <div class="relative flex flex-col md:flex-row">
                <div class="shrink-0 w-full md:w-80 md:h-auto bg-slate-800/50">
                    @if($product->getFirstMediaUrl('images'))
                        <div class="aspect-[3/2] md:aspect-auto md:h-full">
                            <img src="{{ $product->getFirstMediaUrl('images', 'large') }}" class="w-full h-full object-contain p-4">
                        </div>
                    @else
                        <div class="flex h-full w-full items-center justify-center">
                            <svg class="h-20 w-20 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1 p-8 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between">
                            <div>
                                <h1 class="text-3xl font-bold tracking-tight text-white">{{ $product->product_title }}</h1>
                                <p class="mt-1.5 text-sm font-mono text-emerald-400">{{ $product->product_code }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($product->status !== null)
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3.5 py-1.5 text-xs font-semibold shadow-sm {{ $product->status ? 'bg-emerald-500/20 text-emerald-300 ring-1 ring-emerald-500/30' : 'bg-red-500/20 text-red-300 ring-1 ring-red-500/30' }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $product->status ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                                        {{ $product->status ? 'Active' : 'Inactive' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($product->category || $product->subcategory)
                            <div class="mt-4 flex flex-wrap gap-2">
                                @if($product->category)
                                    <span class="inline-flex items-center rounded-lg bg-white/10 px-3 py-1.5 text-xs font-medium text-white/80 ring-1 ring-white/20">{{ $product->category->name }}</span>
                                @endif
                                @if($product->subcategory)
                                    <span class="inline-flex items-center rounded-lg bg-white/10 px-3 py-1.5 text-xs font-medium text-white/80 ring-1 ring-white/20">{{ $product->subcategory->name }}</span>
                                @endif
                                @if($product->connection)
                                    <span class="inline-flex items-center rounded-lg bg-white/10 px-3 py-1.5 text-xs font-medium text-white/80 ring-1 ring-white/20">{{ $product->connection->name }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="mt-6 flex items-center gap-3">
                        <a href="{{ route('client.products.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-medium text-white/80 shadow-sm transition-all hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/30">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- LEFT COLUMN --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- KEY STATS ROW --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">{{ $userPrice ? 'Your Retailer Price' : 'DDP Price' }}</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900 dark:text-white">{{ config('app.currency_symbol') }}{{ number_format($displayPrice, 2) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Weight</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900 dark:text-white">{{ $product->weight ?? '&mdash;' }} <span class="text-sm font-normal text-slate-400">kg</span></p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Width</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900 dark:text-white">{{ $product->width ?? '&mdash;' }} <span class="text-sm font-normal text-slate-400">mm</span></p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Machine Class</p>
                        <p class="mt-1.5 text-2xl font-bold text-slate-900 dark:text-white">{{ $product->machine_class ?? '&mdash;' }} <span class="text-sm font-normal text-slate-400">t</span></p>
                    </div>
                </div>

                {{-- PRODUCT INFO --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                            <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Product Information</h2>
                    </div>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Category</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $product->category->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Subcategory</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $product->subcategory->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Connection Type</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $product->connection->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Drawing Number</dt>
                            <dd class="mt-1 text-sm font-medium text-slate-900 dark:text-white">{{ $product->drawing_number ?? 'N/A' }}</dd>
                        </div>
                        @if($product->product_description)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Description</dt>
                                <dd class="mt-1 text-sm leading-relaxed text-slate-600 break-words dark:text-neutral-300">{!! $product->product_description !!}</dd>
                            </div>
                        @endif
                          @if($product->internal_notes)
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Internal Notes</dt>
                                <dd class="mt-1 text-sm leading-relaxed text-slate-600 break-words dark:text-neutral-300">{!! $product->internal_notes !!}</dd>
                            </div>
                        @endif

                    </dl>
                </div>
                {{-- SPECIFICATIONS --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="mb-5 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                            <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Technical Specifications</h2>
                    </div>
                    @php
                        $specs = [
                            'weight' => ['label' => 'Weight (kg)', 'unit' => 'kg'],
                            'width' => ['label' => 'Width', 'unit' => 'mm'],
                            'volume' => ['label' => 'Volume (m3)', 'unit' => 'm3'],
                            'machine_class' => ['label' => 'Machine class (t)', 'unit' => 't'],
                            'cutting_edge_thickness' => ['label' => 'Cutting edge thickness (mm)', 'unit' => 'mm'],
                            'teeth' => ['label' => 'Teeth', 'unit' => null],
                            'pin_hole' => ['label' => 'Pin Hole (mm)', 'unit' => 'mm'],
                            'pin_center' => ['label' => 'Pin center to Pin center (mm)', 'unit' => 'mm'],
                            'stick_width' => ['label' => 'Stick Width', 'unit' => 'mm'],
                            ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($specs as $field => $config)
                            @php $value = $product->$field; @endphp
                            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 dark:border-neutral-800 dark:bg-neutral-900/50">
                                <dt class="text-xs font-medium text-slate-400">{{ $config['label'] }}</dt>
                                <dd class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">
                                    @if($value !== null && $value !== '')
                                        {{ $value }}
                                        @if($config['unit'])
                                            <span class="text-sm font-normal text-slate-400">{{ $config['unit'] }}</span>
                                        @endif
                                    @else
                                        <span class="text-sm font-normal text-slate-300 dark:text-neutral-600">&mdash;</span>
                                    @endif
                                </dd>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="space-y-6">

                {{-- GALLERY --}}
                @php
                    $gallery = $product->getMedia('gallery');
                    $featureImage = $product->getFirstMediaUrl('images', 'large');
                    $allImages = collect();
                    if ($product->getFirstMedia('images')) {
                        $allImages->push($product->getFirstMedia('images'));
                    }
                    foreach ($gallery as $m) {
                        $allImages->push($m);
                    }
                @endphp
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                    <div class="mb-4 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                            <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <h2 class="text-base font-semibold text-slate-900 dark:text-white">Gallery</h2>
                        <span class="ml-auto rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500 dark:bg-neutral-800 dark:text-neutral-300">{{ $allImages->count() }}</span>
                    </div>

                    @if($allImages->count() > 0)
                        <div class="space-y-4">
                            <div class="relative aspect-[3/2] overflow-hidden rounded-xl bg-slate-100 dark:bg-neutral-800">
                                <img id="adminMainImage" src="{{ $featureImage ?: $allImages->first()->getUrl() }}"
                                     alt="{{ $product->product_title }}"
                                     class="w-full h-full object-contain cursor-pointer transition-opacity duration-300"
                                     onclick="openAdminLightbox('{{ $featureImage ?: $allImages->first()->getUrl() }}')">
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('adminMainImage').click()">
                                    <div class="rounded-full bg-black/50 p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-3 overflow-x-auto pb-2">
                                @foreach($allImages as $index => $media)
                                    <div class="shrink-0 w-20 aspect-[3/2] rounded-lg overflow-hidden border-2 cursor-pointer transition-all hover:opacity-80 {{ $index === 0 ? 'border-emerald-500 ring-2 ring-emerald-500/30' : 'border-slate-200 dark:border-neutral-700' }}"
                                         data-img-src="{{ $media->getUrl() }}"
                                         onclick="switchAdminImage(this, '{{ $media->getUrl() }}', '{{ $media->getUrl() }}')">
                                        <img src="{{ $media->getUrl() }}" alt="" class="w-full h-full object-contain">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <p class="mt-2 text-sm text-slate-400">No images uploaded.</p>
                        </div>
                    @endif
                </div>

                {{-- PDF --}}
                @if($product->getFirstMedia('pdfs'))
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900">
                        <div class="mb-4 flex items-center gap-2.5 border-b border-slate-100 pb-4 dark:border-neutral-800">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/50">
                                <svg class="h-4 w-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <h2 class="text-base font-semibold text-slate-900 dark:text-white">Datasheet</h2>
                        </div>
                        <a href="{{ $product->getFirstMediaUrl('pdfs') }}" target="_blank"
                            class="group flex items-center gap-3.5 rounded-xl border border-slate-200 bg-slate-50 p-4 transition-all hover:border-emerald-300 hover:bg-emerald-50 hover:shadow-sm dark:border-neutral-800 dark:bg-neutral-900/50 dark:hover:border-emerald-700 dark:hover:bg-emerald-900/20">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 dark:bg-red-900/50">
                                <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900 transition-colors group-hover:text-emerald-700 dark:text-white dark:group-hover:text-emerald-400">{{ $product->getFirstMedia('pdfs')->file_name }}</p>
                                <p class="text-xs text-slate-400">PDF &middot; {{ number_format($product->getFirstMedia('pdfs')->size / 1024, 1) }} KB</p>
                            </div>
                            <svg class="h-5 w-5 text-slate-400 transition-colors group-hover:text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function switchAdminImage(el, src, fullSrc) {
            const mainImg = document.getElementById('adminMainImage');
            if (mainImg) {
                mainImg.src = src;
                mainImg.setAttribute('onclick', "openAdminLightbox('" + fullSrc + "')");
            }
            document.querySelectorAll('[data-img-src]').forEach(t => {
                t.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
                t.classList.add('border-slate-200', 'dark:border-neutral-700');
            });
            if (el) {
                el.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-500/30');
                el.classList.remove('border-slate-200', 'dark:border-neutral-700');
            }
        }

        function openAdminLightbox(src) {
            const overlay = document.createElement('div');
            overlay.id = 'adminLightbox';
            overlay.innerHTML = '<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80" onclick="closeAdminLightbox(event)">' +
                '<button class="absolute top-4 right-4 text-white/80 hover:text-white text-3xl" onclick="closeAdminLightbox()">&times;</button>' +
                '<img src="' + src + '" class="max-w-[90vw] max-h-[90vh] object-contain rounded-lg shadow-2xl">' +
            '</div>';
            document.body.appendChild(overlay);
        }

        function closeAdminLightbox(e) {
            if (!e || e.target === e.currentTarget || e.target.tagName === 'BUTTON') {
                const lb = document.getElementById('adminLightbox');
                if (lb) lb.remove();
            }
        }
    </script>
</x-layouts.app>
