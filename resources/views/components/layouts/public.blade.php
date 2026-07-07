<x-layouts.base class="bg-gray-50 text-gray-900 antialiased" x-data="{ mobileMenu: false }">
    <x-partials.public.header />
    {{ $slot }}
    <x-partials.public.footer />

</x-layouts.base>
