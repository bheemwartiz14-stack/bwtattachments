<x-layouts.base class="bg-gray-50 text-gray-900 antialiased" :title="$title ?? null" x-data="{ mobileMenu: false }">
    <div class="min-h-screen flex flex-col">
        <x-partials.public.header />
        {{ $slot }}
        <x-partials.public.footer />
    </div>

</x-layouts.base>
