<x-layouts.public>
    <x-slot:title>{{ $product->product_title ?? 'Product Detail' }} - Attachment Portal</x-slot:title>

    <main class="bg-gray-100 min-h-screen pt-8 pb-14">
        <div class="max-w-[1700px] mx-auto px-8">
            <a href="{{ route('public.home.index') }}" class="inline-flex items-center gap-1 mb-4 text-bwtblue hover:text-bwtblue2 no-underline text-sm font-medium">
                &larr; Back to Products
            </a>

            <div class="bg-white rounded-md shadow-sm p-8">
                <div class="flex flex-col lg:flex-row gap-10">

                    <!-- Gallery -->
                    <div class="w-full lg:w-[45%]">
                        <div class="aspect-[3/2] bg-gray-100 rounded-md overflow-hidden">
                            @php $featureImage = $product->getFirstMediaUrl('images', 'large'); @endphp
                            @if($featureImage)
                                <img src="{{ $featureImage }}" alt="{{ $product->product_title }}" id="mainProductImage" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Main Product Image</div>
                            @endif
                        </div>

                        @php $gallery = $product->getMedia('gallery'); @endphp
                        @if($gallery->count() > 0)
                            <div class="flex gap-3 mt-3">
                                @foreach($gallery as $media)
                                    <div class="flex-1 h-20 bg-gray-100 rounded overflow-hidden cursor-pointer border-2 {{ $loop->first ? 'border-bwtblue' : 'border-transparent' }} hover:border-bwtblue transition-colors"
                                         onclick="document.getElementById('mainProductImage').src='{{ $media->getUrl() }}';document.querySelectorAll('.thumb').forEach(t=>t.classList.remove('border-bwtblue'));this.classList.add('border-bwtblue');">
                                        <img src="{{ $media->getUrl() }}" alt="" class="w-full h-full object-cover">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0 max-w-full">
                        <p class="text-sm text-bwtblue font-semibold mb-1">{{ $product->product_code ?? 'N/A' }}</p>
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $product->product_title ?? 'Product' }}</h2>
                            @if($product->product_description)
                                <div class="text-sm leading-relaxed text-slate-700 break-words overflow-x-auto whitespace-normal [&_img]:max-w-full [&_table]:max-w-full [&_pre]:whitespace-pre-wrap">
                                    {!! $product->product_description !!}
                                </div>
                            @endif

                        @if($product->category || $product->subcategory || $product->connection)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($product->category)
                                    <span class="bg-blue-50 text-bwtblue text-xs font-medium px-3 py-1 rounded">{{ $product->category->name }}</span>
                                @endif
                                @if($product->subcategory)
                                    <span class="bg-green-50 text-green-700 text-xs font-medium px-3 py-1 rounded">{{ $product->subcategory->name }}</span>
                                @endif
                                @if($product->connection)
                                    <span class="bg-yellow-50 text-yellow-700 text-xs font-medium px-3 py-1 rounded">{{ $product->connection->name }}</span>
                                @endif
                            </div>
                        @endif

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
                            <table class="w-full mt-4">
                                @foreach($specRows as $label => $value)
                                    @if($value)
                                        <tr class="border-b border-gray-100">
                                            <td class="py-2.5 pr-4 text-sm font-semibold text-gray-900 w-[40%]">{{ $label }}</td>
                                            <td class="py-2.5 text-sm text-gray-600">{{ $value }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        @endif

                        <div class="mt-6 bg-blue-50 rounded-md p-5">
                            <h3 class="font-semibold text-gray-900 text-sm mb-2">
                                @auth Wholesale Client Area @else Wholesale Client Area &mdash; Login Required @endauth
                            </h3>

                            @auth
                                @php
                                    $userPrice = $product->productPrices->firstWhere('user_id', auth()->id());
                                @endphp
                                @if($userPrice || $product->ddp_price)
                                    <div class="text-2xl font-bold text-green-600 my-3">
                                        {{ config('app.currency_symbol') }} {{ number_format($userPrice->final_price ?? $product->ddp_price, 2) }}
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-500 mb-3">
                                    Log in to view wholesale pricing, request quotations, and download product documents.
                                </p>
                            @endauth

                            <div class="flex flex-wrap gap-3 mt-4">
                                @if($product->getFirstMedia('pdfs'))
                                    <a href="{{ $product->getFirstMediaUrl('pdfs') }}" target="_blank" class="inline-block bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-5 py-2.5 rounded no-underline transition-colors">
                                        Download Technical PDF
                                    </a>
                                @endif

                                @auth
                                    <a href="#" class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2.5 rounded no-underline transition-colors">
                                        Add To Quotation
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-5 py-2.5 rounded no-underline transition-colors">
                                        Login to View Pricing
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

</x-layouts.public>
