@php
    $img = $product->getFirstMediaUrl('images');
    $isFavorited = auth()->check() && $product->favoritedByUsers()->where('user_id', auth()->id())->exists();
@endphp

<div
    class="bg-white border border-gray-200 rounded-md overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col h-full">

    {{-- Image --}}
    <div class="relative">
        <a href="{{ route('public.products.show', $product) }}" wire:navigate>
            <div class="h-56 flex items-center justify-center p-6 bg-white">
                @if ($img)
                    <img src="{{ $img }}" alt="{{ $product->product_title }}" class="max-h-44 object-contain">
                @else
                    <div class="text-gray-400 text-sm">
                        No Image
                    </div>
                @endif
            </div>
        </a>
        @auth
            <button type="button"
                data-favorite="{{ $product->id }}"
                data-favorited="{{ $isFavorited ? 'true' : 'false' }}"
                onclick="toggleFavorite(this)"
                class="absolute bottom-2 right-2 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-white/80 shadow-sm backdrop-blur-sm transition-all hover:scale-110 "
                title="{{ $isFavorited ? 'Remove from favorites' : 'Add to favorites' }}">

                @svg('heroicon-o-heart', 'h-6 w-6 text-red-500' . ($isFavorited ? ' hidden' : ''))
                @svg('heroicon-s-heart', 'h-6 w-6 text-red-500' . ($isFavorited ? '' : ' hidden'))
            </button>
        @endauth
    </div>

    <div class="px-4 pb-4 flex flex-col flex-1">

        {{-- Code --}}
        <p class="font-mono text-xs font-medium text-emerald-600 mt-2">{{ $product->product_code }}</p>

        {{-- Title --}}
        <a href="{{ route('public.products.show', $product) }}" wire:navigate>
            <h3 class="font-semibold text-[14px] leading-5 text-gray-900 line-clamp-2 hover:text-blue-700">
                {{ $product->product_title }}
            </h3>
        </a>

        {{-- Category --}}
        <div class="flex flex-wrap gap-1 mt-2 mb-3">

            @if ($product->category)
                <span class="bg-blue-50 text-blue-700 text-[11px] px-2 py-1 rounded">
                    {{ $product->category->name }}
                </span>
            @endif

            @if ($product->subcategory)
                <span class="bg-green-50 text-green-700 text-[11px] px-2 py-1 rounded">
                    {{ $product->subcategory->name }}
                </span>
            @endif

        </div>

        {{-- Specs + Price --}}
        <div class="flex justify-between gap-4 flex-1">

            {{-- Left --}}
            <div class="flex-1 text-[12px] text-gray-700 space-y-1">

                @if ($product->machine_class)
                    <div>
                        <span class="text-gray-500">Machine Class:</span><br>
                        <strong>{{ $product->machine_class }} t</strong>
                    </div>
                @endif

                @if ($product->connection)
                    <div>
                        <span class="text-gray-500">Connection:</span><br>
                        <strong>{{ $product->connection->name }}</strong>
                    </div>
                @endif

                @if ($product->weight)
                    <div>
                        <span class="text-gray-500">Weight:</span><br>
                        <strong>{{ rtrim(rtrim(number_format($product->weight, 2, '.', ''), '0'), '.') }} kg</strong>
                    </div>
                @endif

                @if ($product->width)
                    <div>
                        <span class="text-gray-500">Width:</span><br>
                        <strong>{{ rtrim(rtrim(number_format($product->width, 2, '.', ''), '0'), '.') }} mm</strong>
                    </div>
                @endif

                @if ($product->volume)
                    <div>
                        <span class="text-gray-500">Volume:</span><br>
                        <strong>{{ rtrim(rtrim(number_format($product->volume, 2, '.', ''), '0'), '.') }} m³</strong>
                    </div>
                @endif

            </div>

            {{-- Right --}}
            <div class="text-right flex flex-col">

                 @auth
                <div>
                    <div class="text-xs text-gray-600 font-semibold uppercase">
                        Wholesale Price
                    </div>
                    @php
                        $userPrice = $product->productPrices->firstWhere('user_id', auth()->id());
                    @endphp

                    <div class="text-2xl font-bold text-green-600 my-3">
                        {{ config('app.currency_symbol') }}
                        {{ number_format($userPrice->final_price ?? $product->ddp_price, 2) }}
                    </div>
                </div>
                @php
                    $quotationRoute = null;

                    if (auth()->check()) {
                        if (auth()->user()->hasRole('Wholesale Client')) {
                            $quotationRoute = route('client.quotations.create');
                        } elseif (auth()->user()->hasRole('Reseller')) {
                            $quotationRoute = route('reseller.quotations.create');
                        }
                    }
                @endphp
                @if ($quotationRoute)
                    <a href="{{ $quotationRoute }}"
                        class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white text-center py-2 rounded text-sm font-semibold">
                        Add To Quotation
                    </a>
                @endif
                @endauth

            </div>

        </div>

    </div>
</div>
