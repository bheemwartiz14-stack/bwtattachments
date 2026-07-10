<header class="bg-bwtblue text-white">
    <div class="max-w-[1700px] mx-auto flex items-center justify-between px-4 sm:px-8 py-4">
        <x-partials.public.logo :url="url('/')" />

        <x-partials.public.navigation />
        <div class="flex items-center gap-3">
            <div class="hidden sm:block">
                <x-partials.public.auth-button />
            </div>
            <button @click="mobileMenu = !mobileMenu"
                class="lg:hidden p-2 text-white hover:text-blue-200 focus:outline-none">
                <svg x-show="!mobileMenu" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg x-show="mobileMenu" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    <x-partials.public.mobile-navigation />
</header>
