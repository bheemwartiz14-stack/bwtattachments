<x-layouts.public>
    <x-slot:title>BWT | Attachment Dealer Network</x-slot:title>

    <section class="text-center pt-8 max-w-[1700px] mx-auto px-5 sm:px-8 aos-init aos-animate">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900" data-aos="fade-up">Attachment Dealer Network
        </h1>
        <p class="text-gray-600 mt-2 text-lg" data-aos="fade-up" data-aos-delay="100">
            Browse Excavator attachments, Wheel Loader attachments, Wear- and Spare parts. Reseller login required to
            view prices.
        </p>
        <div class="">
            <img src="{{ asset('images/unnamed.jpg') }}" alt="Attachments"
                class="mx-auto mt-6 max-w-full h-auto rounded-lg shadow-md" width="1321" height="675"
                data-aos="fade-up" data-aos-delay="200">
        </div>
    </section>

    <main class="bg-gray-100 pt-8 pb-14">
        <div class="max-w-[1700px] mx-auto px-4 sm:px-8" data-aos="fade-up" data-aos-delay="300">
            <livewire:product-filters />
        </div>
    </main>
</x-layouts.public>
