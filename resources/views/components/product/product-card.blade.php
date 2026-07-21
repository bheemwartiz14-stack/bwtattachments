@php
    $userPrice = auth()->check()   ? $product->productPrices->firstWhere('user_id', auth()->id()) : null;
    $img = $product->getFirstMediaUrl('images');
    $isFavorited = auth()->check() && $product->favoritedByUsers()->where('user_id', auth()->id())->exists();
@endphp

<div
    class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-lg transition duration-300 overflow-hidden flex flex-col h-full">

    {{-- Product Image --}}
    <div class="relative">

        <a href="{{ route('public.products.show', $product) }}" wire:navigate>

            <div class="h-44 flex items-center justify-center bg-white p-4">

                @if($img)
                    <img
                        src="{{ $img }}"
                        alt="{{ $product->product_title }}"
                        class="max-h-48 object-contain">
                @else
                    <div class="text-gray-400">
                        No Image
                    </div>
                @endif

            </div>

        </a>

    </div>

    {{-- Body --}}
    <div class="p-4 flex flex-col flex-1">

        {{-- Title --}}
        <a href="{{ route('public.products.show', $product) }}" wire:navigate>

            <h3 class="text-base font-bold text-slate-900 leading-5">
                {{ $product->product_title }}
            </h3>

        </a>

        {{-- Categories --}}
        <div class="flex flex-wrap gap-1.5 mt-2">

            @if($product->category)
                <span
                    class="px-3 py-1 rounded-md bg-blue-50 text-blue-700 text-xs font-medium">
                    {{ $product->category->name }}
                </span>
            @endif

            @if($product->subcategory)
                <span
                    class="px-3 py-1 rounded-md bg-green-50 text-green-700 text-xs font-medium">
                    {{ $product->subcategory->name }}
                </span>
            @endif

        </div>

        {{-- Specs --}}
        <div class="grid grid-cols-2 gap-3 mt-3 flex-1">

            {{-- Left --}}
            <div class="space-y-1.5 text-xs">

                @if($product->machine_class)
                    <div>
                        <p class="text-gray-500">Machine Class:</p>
                        <p class="font-semibold">{{ $product->machine_class }} t</p>
                    </div>
                @endif

                @if($product->connection)
                    <div>
                        <p class="text-gray-500">Connection:</p>
                        <p class="font-semibold">{{ $product->connection->name }}</p>
                    </div>
                @endif

                @if($product->weight)
                    <div>
                        <p class="text-gray-500">Weight:</p>
                        <p class="font-semibold">
                            {{ rtrim(rtrim(number_format($product->weight,2,'.',''),'0'),'.') }} kg
                        </p>
                    </div>
                @endif

                @if($product->width)
                    <div>
                        <p class="text-gray-500">Width:</p>
                        <p class="font-semibold">
                            {{ rtrim(rtrim(number_format($product->width,2,'.',''),'0'),'.') }} mm
                        </p>
                    </div>
                @endif

                @if($product->volume)
                    <div>
                        <p class="text-gray-500">Volume:</p>
                        <p class="font-semibold">
                            {{ rtrim(rtrim(number_format($product->volume,2,'.',''),'0'),'.') }} m³
                        </p>
                    </div>
                @endif

            </div>

            {{-- Right --}}
            <div class="flex flex-col items-end text-right">

                {{-- Product Code --}}
                <div>
                    <p class="font-mono text-sm font-semibold text-emerald-600">
                        {{ $product->product_code }}
                    </p>
                </div>

                {{-- Favorite --}}
                @auth
                    <div class="mt-2">
                        <x-product.favorite-button
                            :product="$product"
                            :is-favorited="$isFavorited" />
                    </div>
                @endauth

                <div class="flex-1"></div>

                @auth

                    {{-- Price --}}
                    <div>

                        <p class="text-sm uppercase font-semibold tracking-wide text-gray-500">
                            Wholesale Price
                        </p>

                       <p class="text-xl font-bold text-green-600 mt-1">
                            {{ config('app.currency_symbol') }}
                            {{ number_format($userPrice->final_price ?? $product->ddp_price,2) }}
                        </p>

                    </div>

                    {{-- Button --}}
                    @role('Wholesale Client|Reseller')

                        <a
                            href="{{ auth()->user()->hasRole('Wholesale Client')
                                ? route('client.quotations.create')
                                : route('reseller.quotations.create') }}"
                            wire:navigate
                            class="mt-3 w-full rounded-md bg-orange-500 hover:bg-orange-600 text-white text-center py-2 text-xs font-semibold transition">

                            Add To Quotation

                        </a>

                    @endrole

                @endauth

            </div>

        </div>

    </div>

</div>
