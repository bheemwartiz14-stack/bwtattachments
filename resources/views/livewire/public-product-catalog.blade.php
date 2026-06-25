<div>
    <section class="hero">
        <h1>Attachment Product Database</h1>
        <p>Browse Excavator Attachments, Wheel Loader Attachments and Wear Parts. Login required to view prices and download technical PDFs.</p>
    </section>

    <div class="container">
        <aside class="sidebar">
            <h3>Filters</h3>

            @if($search || $selectedCategories || $selectedSubcategories || $selectedConnections)
                <div style="margin-bottom:15px;">
                    <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:8px;">
                        @if($search)
                            <span style="display:inline-flex;align-items:center;gap:3px;background:#eef2ff;padding:3px 8px;border-radius:4px;font-size:12px;">
                                Search: &quot;{{ $search }}&quot;
                                <button type="button" wire:click="$set('search', '')" style="background:none;border:none;cursor:pointer;font-size:14px;line-height:1;">&times;</button>
                            </span>
                        @endif
                        @foreach($selectedCategories as $id)
                            @if(isset($this->allCategories[$id]))
                                <span style="display:inline-flex;align-items:center;gap:3px;background:#eef2ff;padding:3px 8px;border-radius:4px;font-size:12px;">
                                    {{ $this->allCategories[$id] }}
                                    <button type="button" wire:click="removeFilter('category', '{{ $id }}')" style="background:none;border:none;cursor:pointer;font-size:14px;line-height:1;">&times;</button>
                                </span>
                            @endif
                        @endforeach
                        @foreach($selectedSubcategories as $id)
                            @if(isset($this->allSubcategories[$id]))
                                <span style="display:inline-flex;align-items:center;gap:3px;background:#eef2ff;padding:3px 8px;border-radius:4px;font-size:12px;">
                                    {{ $this->allSubcategories[$id] }}
                                    <button type="button" wire:click="removeFilter('subcategory', '{{ $id }}')" style="background:none;border:none;cursor:pointer;font-size:14px;line-height:1;">&times;</button>
                                </span>
                            @endif
                        @endforeach
                        @foreach($selectedConnections as $id)
                            @if(isset($this->allConnections[$id]))
                                <span style="display:inline-flex;align-items:center;gap:3px;background:#eef2ff;padding:3px 8px;border-radius:4px;font-size:12px;">
                                    {{ $this->allConnections[$id] }}
                                    <button type="button" wire:click="removeFilter('connection', '{{ $id }}')" style="background:none;border:none;cursor:pointer;font-size:14px;line-height:1;">&times;</button>
                                </span>
                            @endif
                        @endforeach
                    </div>
                    <button type="button" wire:click="resetFilters" style="background:#e74c3c;color:white;border:none;padding:6px 12px;border-radius:4px;cursor:pointer;font-size:13px;width:100%;">Clear All</button>
                </div>
            @endif

            <div class="filter-group">
                <label><strong>Category</strong></label>
                @foreach($this->allCategories as $id => $name)
                    <label>
                        <input type="checkbox" value="{{ $id }}" wire:model.live="selectedCategories">
                        {{ $name }}
                    </label>
                @endforeach
            </div>

            <div class="filter-group">
                <label><strong>Subcategory</strong></label>
                @foreach($this->allSubcategories as $id => $name)
                    <label>
                        <input type="checkbox" value="{{ $id }}" wire:model.live="selectedSubcategories">
                        {{ $name }}
                    </label>
                @endforeach
            </div>

            <div class="filter-group">
                <label><strong>Connection</strong></label>
                @foreach($this->allConnections as $id => $name)
                    <label>
                        <input type="checkbox" value="{{ $id }}" wire:model.live="selectedConnections">
                        {{ $name }}
                    </label>
                @endforeach
            </div>
        </aside>

        <main class="content">
            <div class="searchbar">
                <input type="search" placeholder="Search by code or description..." wire:model.live.debounce.300ms="search">
                @if($search)
                    <button type="button" wire:click="$set('search', '')" class="clear-btn">Clear</button>
                @endif
            </div>

            @if($this->products->count())
                <div class="products">
                    @foreach($this->products as $product)
                        <a href="{{ route('public.products.show', $product->id) }}" class="product-card">
                            <div class="product-image">
                                @if($product->getFirstMediaUrl('images', 'thumb'))
                                    <img src="{{ $product->getFirstMediaUrl('images', 'thumb') }}" alt="{{ $product->product_title }}">
                                @else
                                    No Image
                                @endif
                            </div>
                            <div class="product-body">
                                <div class="product-code">{{ $product->product_code ?? $product->code }}</div>
                                <div>{{ $product->product_title ?? $product->description }}</div>
                                <div class="specs">
                                    @if($product->weight)
                                        <div>Weight: {{ $product->weight }}</div>
                                    @endif
                                    @if($product->width)
                                        <div>Width: {{ $product->width }}</div>
                                    @endif
                                    @if($product->machine_class ?? $product->machineClass)
                                        <div>Class: {{ $product->machine_class ?? $product->machineClass }}</div>
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
                    {{ $this->products->links() }}
                </div>
            @else
                <div class="detail-page" style="text-align:center;padding:60px 20px;">
                    <h2 style="margin-bottom:10px;">No products found</h2>
                    <p style="color:#666;">Try adjusting your search or filter criteria.</p>
                </div>
            @endif
        </main>
    </div>
</div>
