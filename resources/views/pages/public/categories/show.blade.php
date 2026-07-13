<x-layouts.public>
    <x-slot:title>{{ $category->name }} - BWT</x-slot:title>

    <div class="bg-gray-50 min-h-screen">
        <main class="max-w-[1700px] mx-auto px-4 sm:px-8 py-10">
            {{-- Breadcrumb + Header --}}
            <div class="mb-8">
                <a href="{{ route('public.home.index') }}" wire:navigate
                    class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
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
                        <x-product.product-card :product="$product" />
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
