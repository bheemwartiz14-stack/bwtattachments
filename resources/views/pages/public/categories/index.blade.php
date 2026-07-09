<x-layouts.public>
    <x-slot:title>Categories - {{ config('app.name') }}</x-slot:title>

    <div class="container">
        <main class="content">
            <h1 style="margin-bottom:20px;font-size:28px;">Product Categories</h1>
            <p style="margin-bottom:30px;color:#666;">Browse products by category.</p>

            @if(count($categories))
                <div class="products">
                    @foreach($categories as $id => $name)
                        <a href="{{ route('public.categories.show', $id) }}" wire:navigate class="product-card">
                            <div class="product-body" style="padding:30px;text-align:center;">
                                <div style="font-size:18px;font-weight:bold;color:#1d2939;margin-bottom:8px;">{{ $name }}</div>
                                <span class="view-btn" style="display:inline-block;width:auto;padding:8px 24px;">View Products</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="detail-page" style="text-align:center;padding:60px 20px;">
                    <h2 style="margin-bottom:10px;">No categories found</h2>
                    <p style="color:#666;">There are no product categories available at this time.</p>
                </div>
            @endif
        </main>
    </div>
</x-layouts.public>
