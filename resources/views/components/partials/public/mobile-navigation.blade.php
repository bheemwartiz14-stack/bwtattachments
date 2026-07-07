<div x-show="mobileMenu" x-cloak x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2" @click.away="mobileMenu = false"
    class="lg:hidden border-t border-white/20">
    <div class="px-4 py-4 space-y-1">
        <x-partials.public.navigation-links :mobile="true" />
        <hr class="border-white/20 my-2">
        <x-partials.public.auth-button :mobile="true" />
    </div>
</div>
