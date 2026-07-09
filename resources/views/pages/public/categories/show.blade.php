<x-layouts.public>
    <x-slot:title>{{ $category->name }} - BWT</x-slot:title>

    <div class="bg-gray-50 min-h-screen">
        <main class="max-w-[1700px] mx-auto px-4 sm:px-8 py-10">
            {{-- Breadcrumb + Header --}}
            <div class="mb-8">
                <a href="{{ route('public.home.index') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to all products
                </a>
                <div class="border-b border-gray-200 pb-5">
                    <h1 class="text-3xl sm:text-4xl font-bold tracking-tight text-gray-900">{{ $category->name }}</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ $products->count() }} product(s)</p>
                </div>
            </div>

            {{-- Products Grid --}}
            @if ($products->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
                        @php $img = $product->getFirstMediaUrl('images'); @endphp
                        <div class="bg-white rounded-md shadow-sm overflow-hidden flex flex-col">
                            <div class="p-6 pb-2 flex items-center justify-center">
                                @if ($img)
                                    <img src="{{ $img }}" alt="{{ $product->product_title }}" class="h-40 object-contain" />
                                @else
                                    <div class="h-40 flex items-center justify-center text-gray-400 text-sm">No Image</div>
                                @endif
                            </div>
                            <div class="px-6 pt-2 flex-1 flex flex-col">
                                <h3 class="font-bold text-[15px] text-gray-900 leading-snug">{{ $product->product_title }}</h3>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @if ($product->category)
                                        <span class="bg-blue-50 text-bwtblue text-xs font-medium px-2.5 py-1 rounded">{{ $product->category->name }}</span>
                                    @endif
                                    @if ($product->subcategory)
                                        <span class="bg-green-50 text-green-700 text-xs font-medium px-2.5 py-1 rounded">{{ $product->subcategory->name }}</span>
                                    @endif
                                </div>

                                <div class="text-sm text-gray-700 space-y-0.5 mb-4">
                                    @if ($product->machine_class)
                                        <p><span class="text-gray-500">Machine Class:</span> {{ $product->machine_class }} t</p>
                                    @endif
                                    @if ($product->connection)
                                        <p><span class="text-gray-500">Connection:</span> {{ $product->connection->name }}</p>
                                    @endif
                                    @if ($product->weight)
                                        <p><span class="text-gray-500">Weight:</span> {{ rtrim(rtrim(number_format($product->weight, 2, '.', ''), '0'), '.') }} kg</p>
                                    @endif
                                    @if ($product->width)
                                        <p><span class="text-gray-500">Width:</span> {{ rtrim(rtrim(number_format($product->width, 2, '.', ''), '0'), '.') }} mm</p>
                                    @endif
                                    @if ($product->volume)
                                        <p><span class="text-gray-500">Volume:</span> {{ rtrim(rtrim(number_format($product->volume, 2, '.', ''), '0'), '.') }} m³</p>
                                    @endif
                                </div>

                                <a href="{{ route('public.products.show', $product) }}" wire:navigate class="mt-auto w-full bg-bwtblue hover:bg-bwtblue2 transition-colors text-white font-medium py-3 rounded text-center no-underline block">
                                    View details
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-16 text-lg">
                    No products in this category.
                </div>
            @endif
        </main>
    </div>

</x-layouts.public>
