<x-layouts.public>
  <x-slot:title>{{ $category->name }} - BWT</x-slot:title>

  <section class="bg-gradient-to-b from-bwtblue to-bwtblue2 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
      <a href="{{ route('public.home.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-200 hover:text-white transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to all products
      </a>
      <h1 class="text-4xl sm:text-5xl font-bold tracking-tight">{{ $category->name }}</h1>
      <p class="mt-3 text-lg text-blue-200">{{ $products->count() }} product(s)</p>
    </div>
  </section>

  <main class="bg-gray-100 py-12">
    <div class="max-w-[1700px] mx-auto px-4 sm:px-6 lg:px-8">

      @if($products->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          @foreach($products as $product)
            @php $img = $product->getFirstMediaUrl('images'); @endphp
            <div class="bg-white rounded-md shadow-sm overflow-hidden flex flex-col">
              <div class="p-6 pb-2 flex items-center justify-center">
                @if($img)
                  <img src="{{ $img }}" alt="{{ $product->product_title }}" class="h-40 object-contain" />
                @else
                  <div class="h-40 flex items-center justify-center text-gray-400 text-sm">No Image</div>
                @endif
              </div>
              <div class="px-5">
                <p class="text-[11px] text-gray-500 border-t border-gray-100 pt-2">{{ $product->product_code }}</p>
              </div>
              <div class="px-5 pt-2 flex-1 flex flex-col">
                <h3 class="font-bold text-[15px] text-gray-900 leading-snug">{{ $product->product_code }}</h3>
                <p class="text-gray-700 text-sm mt-1 mb-3">{{ $product->product_title ?? $product->product_description }}</p>

                <div class="flex flex-wrap gap-2 mb-3">
                  @if($product->category)
                    <span class="bg-blue-50 text-bwtblue text-xs font-medium px-2.5 py-1 rounded">{{ $product->category->name }}</span>
                  @endif
                  @if($product->subcategory)
                    <span class="bg-green-50 text-green-700 text-xs font-medium px-2.5 py-1 rounded">{{ $product->subcategory->name }}</span>
                  @endif
                </div>

                <div class="text-sm text-gray-700 space-y-0.5 mb-4">
                  @if($product->machine_class)
                    <p><span class="text-gray-500">Machine Class:</span> {{ $product->machine_class }} t</p>
                  @endif
                  @if($product->connection)
                    <p><span class="text-gray-500">Connection:</span> {{ $product->connection->name }}</p>
                  @endif
                  @if($product->weight)
                    <p><span class="text-gray-500">Weight:</span> {{ $product->weight }} kg</p>
                  @endif
                  @if($product->width)
                    <p><span class="text-gray-500">Width:</span> {{ $product->width }} mm</p>
                  @endif
                  @if($product->volume)
                    <p><span class="text-gray-500">Volume:</span> {{ $product->volume }} L</p>
                  @endif
                </div>

                <a href="{{ route('public.products.show', $product) }}" class="mt-auto w-full bg-bwtblue hover:bg-bwtblue2 transition-colors text-white font-medium py-3 rounded text-center no-underline block">
                  View details
                </a>
              </div>
            </div>
          @endforeach
        </div>

        @if(method_exists($products, 'links'))
          <div class="mt-8">
            {{ $products->links() }}
          </div>
        @endif
      @else
        <div class="text-center text-gray-500 py-16 text-lg">
          No products in this category.
        </div>
      @endif

    </div>
  </main>

</x-layouts.public>
