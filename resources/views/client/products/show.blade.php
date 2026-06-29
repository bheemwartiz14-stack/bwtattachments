<x-layouts.app>
    <x-slot:title>{{ $product->product_code }} - Attachment Portal</x-slot:title>
    <x-breadcrumb :items="[['label' => 'Client Portal', 'url' => route('client.dashboard')], ['label' => 'Products', 'url' => route('client.products.index')], ['label' => $product->product_code]]" />

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-900/30 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-medium text-red-800 dark:border-red-900/50 dark:bg-red-900/30 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 space-y-6">
                <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                    <div class="aspect-[3/2] bg-slate-100 dark:bg-neutral-900 relative overflow-hidden">
                        @if($product->getFirstMedia('images'))
                            <img src="{{ $product->getFirstMediaUrl('images', 'large') }}" alt="{{ $product->product_code }}" class="w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400 dark:text-neutral-500">
                                <svg class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif
                    </div>
                    @php $gallery = $product->getMedia('gallery'); @endphp
                    @if($gallery && $gallery->count() > 1)
                        <div class="flex gap-2 p-4 overflow-x-auto">
                            @foreach($gallery as $media)
                                <div class="w-20 shrink-0 rounded-lg bg-slate-100 dark:bg-neutral-900 overflow-hidden border border-slate-200 dark:border-neutral-800 aspect-[3/2]">
                                    <img src="{{ $media->getUrl() }}" alt="" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950">
                    <h2 class="text-base font-semibold text-slate-950 dark:text-neutral-100 mb-4">Specifications</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                         @php
                            $specRows = [
                                'Product Code' => $product->product_code,
                                'Weight' => $product->weight ? $product->weight . ' kg' : null,
                                'Width' => $product->width ? $product->width . ' mm' : null,
                                'Volume' => $product->volume ? $product->volume . ' L' : null,
                                'Machine Class' => $product->machine_class ? $product->machine_class . ' t' : null,
                                'Cutting Edge Thickness' => $product->cutting_edge_thickness ? $product->cutting_edge_thickness . ' mm' : null,
                                'Teeth' => $product->teeth,
                                'Pin Hole' => $product->pin_hole ? $product->pin_hole . ' mm' : null,
                                'Pin Center' => $product->pin_center ? $product->pin_center . ' mm' : null,
                                'Stick Width' => $product->stick_width ? $product->stick_width . ' mm' : null,
                            ];
                            $hasSpecs = collect($specRows)->filter()->isNotEmpty();
                        @endphp
                        @if($hasSpecs)
                       @foreach($specRows as $label => $value)
                                    @if($value)
                                       <dt class="text-xs font-medium text-gray-400 dark:text-neutral-500 uppercase tracking-wider">{{ $label }}</dt>
                                <dd class="mt-1 text-sm font-medium text-black dark:text-neutral-100">{{ $value }}</dd>
                                    @endif
                                @endforeach
                          @endif
               
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-950 sticky top-20">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">{{ $product->category?->name ?? 'Product' }}</span>
                    </div>
                    <p class="text-xs font-mono font-medium text-emerald-600 dark:text-emerald-400">{{ $product->product_code }}</p>
                    <h1 class="text-xl font-bold text-slate-950 dark:text-neutral-100 mt-1">{{ $product->description }}</h1>

                    @php
                        $userPrice = $product->productPrices->firstWhere('user_id', auth()->id());
                        $displayPrice = $userPrice?->price ?? $product->ddp_price;
                    @endphp
                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-neutral-800">
                        <p class="text-xs font-medium text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-1">{{ $userPrice ? 'Your Wholesale Price' : 'DDP Price' }}</p>
                        <p class="text-3xl font-bold text-black dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format($displayPrice, 2) }}</p>
                    </div>

                    @php $wholesalePrice = $product->productPrices->where('type', 'wholesale_purchase')->firstWhere('user_id', auth()->id()); @endphp
                    @if($wholesalePrice)
                    <div class="mt-6 pt-6 border-t border-slate-100 dark:border-neutral-800">
                        <p class="text-xs font-medium text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-3">Manage Product Price</p>
                        <form method="POST" action="{{ route('client.products.margin', $product->id) }}" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-xs font-medium text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-1.5">Product Price</label>
                                <p class="text-lg font-bold text-black dark:text-neutral-100">{{ config('app.currency_symbol') }}{{ number_format($wholesalePrice->price, 2) }}</p>
                            </div>
                            <div>
                                <label for="margin" class="block text-xs font-medium text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-1.5">Margin (%)</label>
                                <input type="number" name="margin" id="margin" value="{{ old('margin', $wholesalePrice->margin) }}"
                                    class="margin-input block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-black placeholder-slate-400 transition-colors focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:placeholder-neutral-500"
                                    min="0" max="100" step="0.01" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-400 dark:text-neutral-500 uppercase tracking-wider mb-1.5">Total Price</label>
                                <p class="total-display text-2xl font-bold text-emerald-700 dark:text-emerald-400">
                                    {{ config('app.currency_symbol') }}{{ number_format($wholesalePrice->price * (1 + ($wholesalePrice->margin ?? 0) / 100), 2) }}
                                </p>
                            </div>
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                Save Margin
                            </button>
                        </form>
                    </div>
                    @endif



                    @php $pdf = $product->getFirstMedia('pdfs'); @endphp
                    @if($pdf)
                        <div class="mt-4">
                            <a href="{{ $pdf->getUrl() }}" target="_blank" class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-normal text-black shadow-sm transition-colors hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-100 dark:hover:bg-neutral-800">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                Download PDF Datasheet
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const marginInput = document.querySelector('.margin-input');
        if (marginInput) {
            const totalDisplay = document.querySelector('.total-display');
            const basePrice = {{ $wholesalePrice?->price ?? 0 }};
            const currency = '{{ config('app.currency_symbol') }}';

            function updateTotal() {
                const margin = parseFloat(marginInput.value) || 0;
                const total = basePrice * (1 + margin / 100);
                totalDisplay.textContent = currency + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            marginInput.addEventListener('input', updateTotal);
        }
    </script>
    @endpush
</x-layouts.app>
