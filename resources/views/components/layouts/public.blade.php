<x-layouts.base class="bg-gray-50 text-gray-900 antialiased" x-data="{ mobileMenu: false }">
    <div class="min-h-screen flex flex-col">
        <x-partials.public.header />
        {{ $slot }}
        <x-partials.public.footer />
    </div>

</x-layouts.base>
