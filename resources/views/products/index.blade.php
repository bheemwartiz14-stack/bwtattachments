<x-layouts.public>
    <x-slot:title>Products - Attachment Portal</x-slot:title>

    <section class="hero">
        <h1>Attachment Product Database</h1>
        <p>Browse Excavator Attachments, Wheel Loader Attachments and Wear Parts. Login required to view prices and download technical PDFs.</p>
    </section>

    <div class="container">
        <aside class="sidebar">
            <h3>Filters</h3>

            <form method="GET" action="{{ route('public.products.index') }}" id="filter-form">
                <div class="filter-group">
                    <strong>Category</strong><br><br>
                    @foreach($categories ?? [] as $id => $name)
                        <label>
                            <input type="radio" name="category" value="{{ $id }}" class="category-radio" {{ (string)request('category') === (string)$id ? 'checked' : '' }}>
                            {{ $name }}
                        </label>
                    @endforeach
                </div>

                <div class="filter-group">
                    <strong>Sub Category</strong><br><br>
                    <div id="subcategory-list">
                        @foreach($subcategories ?? [] as $subcategory)
                            <label class="subcategory-item" data-category-id="{{ $subcategory->category_id }}">
                                <input type="checkbox" name="subcategory[]" value="{{ $subcategory->id }}" {{ in_array((string)$subcategory->id, (array)request('subcategory', [])) ? 'checked' : '' }}>
                                {{ $subcategory->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="filter-group">
                    <strong>Connection</strong><br><br>
                    @foreach($connections ?? [] as $id => $name)
                        <label>
                            <input type="checkbox" name="connection[]" value="{{ $id }}" {{ in_array($id, (array)request('connection', [])) ? 'checked' : '' }}>
                            {{ $name }}
                        </label>
                    @endforeach
                </div>

                <button type="submit" style="width:100%;padding:10px;background:#1d2939;color:white;border:none;border-radius:5px;cursor:pointer;font-size:14px;">Apply Filters</button>
                @if(request()->anyFilled(['category', 'subcategory', 'connection', 'search']))
                    <a href="{{ route('public.products.index') }}" style="display:block;text-align:center;margin-top:10px;padding:10px;background:#e74c3c;color:white;border:none;border-radius:5px;text-decoration:none;font-size:14px;">Clear Filters</a>
                @endif
            </form>
        </aside>

        <main class="content">
            <form method="GET" action="{{ route('public.products.index') }}" class="searchbar">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product code or description">
                <button type="submit">Search</button>
                @if(request()->anyFilled(['search', 'category', 'subcategory', 'connection']))
                    <a href="{{ route('public.products.index') }}" class="clear-btn">Clear</a>
                @endif
            </form>

            @if($products->count())
                <div class="products">
                    @foreach($products as $product)
                        <a href="{{ route('public.products.show', $product) }}" class="product-card">
                            <div class="product-image">
                                @php $img = $product->getFirstMediaUrl('images', 'small'); @endphp
                                @if($img)
                                    <img src="{{ $img }}" alt="{{ $product->product_title }}" class="aspect-[3/2] w-full object-cover">
                                @else
                                    <div class="aspect-[3/2] w-full flex items-center justify-center bg-slate-100 text-slate-400 text-sm">Product Image</div>
                                @endif
                            </div>
                            <div class="product-body">
                                <div class="product-code">{{ $product->product_code ?? 'N/A' }}</div>
                                <div>{{ $product->product_title ?? $product->product_description ?? 'Product' }}</div>

                                @if($product->category || $product->subcategory)
                                    <div>
                                        @if($product->category)
                                            <span class="category-tag">{{ $product->category->name }}</span>
                                        @endif
                                        @if($product->subcategory)
                                            <span class="category-tag" style="background:#e6f7ed;">{{ $product->subcategory->name }}</span>
                                        @endif
                                    </div>
                                @endif

                                <div class="specs">
                                    @if($product->machine_class)
                                        <div>Machine Class: {{ $product->machine_class }} t</div>
                                    @endif
                                    @if($product->connection)
                                        <div>Connection: {{ $product->connection->name }}</div>
                                    @endif
                                    @if($product->weight)
                                        <div>Weight: {{ $product->weight }} kg</div>
                                    @endif
                                    @if($product->width)
                                        <div>Width: {{ $product->width }} mm</div>
                                    @endif
                                    @if($product->volume)
                                        <div>Volume: {{ $product->volume }} L</div>
                                    @endif
                                </div>

                                <div class="product-buttons">
                                    <span class="view-btn">View Details</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="pagination">
                    {{ $products->links() }}
                </div>
            @else
                <div style="text-align:center;padding:60px 20px;background:white;border-radius:8px;">
                    <h3 style="margin-bottom:10px;">No products found</h3>
                    <p style="color:#666;">Try adjusting your search or filter criteria.</p>
                </div>
            @endif
        </main>
    </div>
</x-layouts.public>

<script>
(function() {
    var categoryRadios = document.querySelectorAll('.category-radio');
    var subcategoryItems = document.querySelectorAll('.subcategory-item');

    function filterSubcategories() {
        var selected = null;
        categoryRadios.forEach(function(rb) {
            if (rb.checked) selected = rb.value;
        });

        subcategoryItems.forEach(function(item) {
            var catId = item.getAttribute('data-category-id');
            if (selected === null) {
                item.style.display = '';
            } else {
                item.style.display = selected === catId ? '' : 'none';
                if (selected !== catId) {
                    var checkbox = item.querySelector('input[type="checkbox"]');
                    if (checkbox) checkbox.checked = false;
                }
            }
        });
    }

    categoryRadios.forEach(function(rb) {
        rb.addEventListener('change', filterSubcategories);
    });

    filterSubcategories();
})();
</script>
