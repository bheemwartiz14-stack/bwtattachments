<x-layouts.public>
    <x-slot:title>BWT | Attachment Dealer Network</x-slot:title>

    <section class="text-center pt-8 max-w-[1700px] mx-auto px-5 sm:px-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Attachment Dealer Network
        </h1>
        <p class="text-gray-600 mt-2 text-lg">
            Browse Excavator attachments, Wheel Loader attachments, Wear- and Spare parts. Reseller login required to
            view prices.
        </p>
        <div class="">
            <img src="{{ asset('images/unnamed.jpg') }}" alt="Attachments"
                class="mx-auto mt-6 max-w-full h-auto rounded-lg shadow-md" width="1321" height="675">
        </div>
    </section>

    <main class="bg-gray-100 pt-8 pb-14">
        <div class="max-w-[1700px] mx-auto px-4 sm:px-8">
            <livewire:product-filters />
        </div>
    </main>
        @push('scripts')
        <script src="{{ asset('assets/js/product.js') }}"></script>
    @endpush
</x-layouts.public>
