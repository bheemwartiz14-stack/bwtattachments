<x-layouts.public>
    <x-slot:title>{{ $product->product_title ?? 'Product Detail' }} - Attachment Portal</x-slot:title>

    <div class="container">
        <main class="content">
            <a href="{{ route('public.products.index') }}" style="display:inline-flex;align-items:center;gap:5px;margin-bottom:15px;color:#1d2939;text-decoration:none;font-size:14px;">
                &larr; Back to Products
            </a>

            <div class="detail-page">
                <div class="detail-layout">
                    <div class="gallery">
                        <div class="main-image">
                            @php $featureImage = $product->getFirstMediaUrl('images', 'large'); @endphp
                            @if($featureImage)
                                <img src="{{ $featureImage }}" alt="{{ $product->product_title }}" id="mainProductImage">
                            @else
                                Main Product Image
                            @endif
                        </div>

                        @php $gallery = $product->getMedia('gallery'); 
                        @endphp
                        @if($gallery->count() > 0)
                            <div class="thumbs">
                                @foreach($gallery as $media)
                                    <div class="thumb {{ $loop->first ? 'active' : '' }}" onclick="document.getElementById('mainProductImage').src='{{ $media->getUrl() }}';document.querySelectorAll('.thumb').forEach(t=>t.classList.remove('active'));this.classList.add('active');">
                                        <img src="{{ $media->getUrl() }}" alt="">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="product-info">
                        <p style="font-size:13px;color:#0f766e;font-weight:600;margin-bottom:5px;">{{ $product->product_code ?? 'N/A' }}</p>
                        <h2>{{ $product->product_title ?? 'Product' }}</h2>

                        @if($product->product_description)
                            <div class="desc">{!! $product->product_description !!}</div>
                        @endif

                        @if($product->category || $product->subcategory || $product->connection)
                            <div style="margin-bottom:15px;">
                                @if($product->category)
                                    <span class="category-tag">{{ $product->category->name }}</span>
                                @endif
                                @if($product->subcategory)
                                    <span class="category-tag" style="background:#e6f7ed;">{{ $product->subcategory->name }}</span>
                                @endif
                                @if($product->connection)
                                    <span class="category-tag" style="background:#fef3c7;">{{ $product->connection->name }}</span>
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
                            <table class="spec-table">
                                @foreach($specRows as $label => $value)
                                    @if($value)
                                        <tr>
                                            <td>{{ $label }}</td>
                                            <td>{{ $value }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        @endif

                        <div class="client-box">
                            <h3>
                                @auth Wholesale Client Area @else Wholesale Client Area &mdash; Login Required @endauth
                            </h3>

                            @auth
                                @php
                                    $userPrice = $product->productPrices->firstWhere('user_id', auth()->id());
                                @endphp
                                @if($userPrice || $product->ddp_price)
                                    <div class="price">
                                        {{ config('app.currency_symbol') }} {{ number_format($userPrice->final_price ?? $product->ddp_price, 2) }}
                                    </div>
                                @endif
                            @else
                                <p style="font-size:14px;color:#555;margin-bottom:15px;">
                                    Log in to view wholesale pricing, request quotations, and download product documents.
                                </p>
                            @endauth

                            @if($product->getFirstMedia('pdfs'))
                                <a href="{{ $product->getFirstMediaUrl('pdfs') }}" target="_blank" class="download-btn">
                                    Download Technical PDF
                                </a>
                            @endif

                            @auth
                                <a href="#" class="quote-btn">Add To Quotation</a>
                            @else
                                <a href="{{ route('login') }}" class="quote-btn">Login to View Pricing</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-layouts.public>
