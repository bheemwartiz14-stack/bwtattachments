<x-layouts.public>
  <x-slot:title>BWT | Wholesale Attachment Product Database</x-slot:title>

  <section class="text-center pt-10 pb-6 px-4">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Wholesale Attachment Product Database</h1>
    <p class="text-gray-600 mt-2 text-lg">
      Browse Excavator attachments, Wheel Loader attachments, Wear- and Spare parts. Reseller login required to view prices.
    </p>
  </section>

  <main class="bg-gray-100 pt-8 pb-14">
    <div class="max-w-[1700px] mx-auto px-8">

      <form method="GET" action="{{ url('/') }}" class="bg-white rounded-md shadow-sm flex items-stretch overflow-hidden mb-6">
        <input
          name="search"
          type="text"
          value="{{ request('search') }}"
          placeholder="Search product code or description"
          class="flex-1 px-5 py-4 text-gray-700 placeholder-gray-400 focus:outline-none"
        />
        <button type="submit" class="bg-black hover:bg-gray-800 transition-colors text-white font-medium px-8 whitespace-nowrap">
          Search
        </button>
      </form>

      <form method="GET" action="{{ url('/') }}" class="bg-white rounded-md shadow-sm px-6 py-4 mb-8 flex flex-wrap items-center gap-6">
        <span class="font-semibold text-gray-800">Filters:</span>

        <select id="filterCategory" name="category" class="font-semibold text-gray-800 bg-transparent border-none focus:outline-none cursor-pointer">
          <option value="">Category</option>
          @foreach($categories ?? [] as $id => $name)
            <option value="{{ $id }}" {{ (string)request('category') === (string)$id ? 'selected' : '' }}>{{ $name }}</option>
          @endforeach
        </select>

        <select id="filterSubcategory" name="subcategory" class="font-semibold text-gray-800 bg-transparent border-none focus:outline-none cursor-pointer">
          <option value="">Sub Category</option>
          @foreach($subcategories ?? [] as $sub)
            <option value="{{ $sub->id }}" data-category-id="{{ $sub->category_id }}" {{ (string)request('subcategory') === (string)$sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
          @endforeach
        </select>

        <select name="connection" class="font-semibold text-gray-800 bg-transparent border-none focus:outline-none cursor-pointer">
          <option value="">Connection</option>
          @foreach($connections ?? [] as $id => $name)
            <option value="{{ $id }}" {{ (string)request('connection') === (string)$id ? 'selected' : '' }}>{{ $name }}</option>
          @endforeach
        </select>

        <button type="submit" class="ml-auto bg-black hover:bg-gray-800 transition-colors text-white font-medium px-6 py-2.5 rounded">
          Apply filters
        </button>

        @if(request()->anyFilled(['search', 'category', 'subcategory', 'connection']))
          <a href="{{ url('/') }}" class="bg-red-500 hover:bg-red-600 transition-colors text-white font-medium px-6 py-2.5 rounded no-underline">
            Clear
          </a>
        @endif
      </form>

      @if($products->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          @foreach($products as $product)
            @php $img = $product->getFirstMediaUrl('images', 'small'); @endphp
            <div class="product-card bg-white rounded-md shadow-sm overflow-hidden flex flex-col">
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
                   <p>
        <span class="text-gray-500">Weight:</span>
        {{ rtrim(rtrim(number_format($product->weight, 2, '.', ''), '0'), '.') }} kg
    </p>
                  @endif
                  @if($product->width)
                    <p><span class="text-gray-500">Width:</span> {{ rtrim(rtrim(number_format($product->width, 2, '.', ''), '0'), '.') }} mm</p>
                  @endif
                  @if($product->volume)
                    <p><span class="text-gray-500">Volume:</span> {{ rtrim(rtrim(number_format($product->volume, 2, '.', ''), '0'), '.') }} m³</p>
                  @endif
                </div>

                <a href="{{ route('public.products.show', $product) }}" class="mt-auto w-full bg-bwtblue hover:bg-bwtblue2 transition-colors text-white font-medium py-3 rounded text-center no-underline block">
                  View details
                </a>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-8">
          {{ $products->links() }}
        </div>
      @else
        <div class="text-center text-gray-500 py-16 text-lg">
          No products match your search.
        </div>
      @endif

    </div>
  </main>

  <script>
    document.getElementById('filterCategory')?.addEventListener('change', function() {
      const selected = this.value;
      const subSelect = document.getElementById('filterSubcategory');
      const options = subSelect.querySelectorAll('option');
      options.forEach(opt => {
        if (!opt.value || opt.dataset.categoryId === selected || !selected) {
          opt.style.display = '';
        } else {
          opt.style.display = 'none';
        }
      });
      subSelect.value = '';
    });
  </script>

</x-layouts.public>
