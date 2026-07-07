<x-layouts.base class="font-sans antialiased">

    <div class="flex min-h-screen flex-col bg-white dark:bg-black">
        <x-partials.app.header />

        <div class="mx-auto grid w-full max-w-[1500px] flex-1 gap-6 px-4 py-6 sm:px-6 lg:grid-cols-[280px_minmax(0,1fr)] lg:px-8">
            <x-partials.app.sidebar />
            <main class="min-w-0 space-y-6">
                {{ $slot }}
            </main>
        </div>
        <x-partials.app.footer />
    </div>
</x-layouts.base>
