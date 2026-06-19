<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="darkMode()">
    <head>
        @include('components.layouts.head')
    </head>

    <body class="font-sans antialiased" x-data="sidebar()">
        <div class="min-h-screen bg-white dark:bg-slate-950">
            @auth
                @include('components.layouts.navigation')
            @endauth

            @isset($header)
                <!-- <header class="border-b border-slate-100 bg-white">
                    <div class="mx-auto max-w-[1500px] px-4 py-5 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header> -->
            @endisset

            @auth
                <div class="mx-auto grid max-w-[1500px] gap-6 px-4 py-6 sm:px-6 lg:grid-cols-[280px_minmax(0,1fr)] lg:px-8">
                    @include('components.layouts.sidebar')

                    <main class="min-w-0 space-y-6">
                        {{ $slot }}
                    </main>
                </div>
            @else
                <div class="mx-auto max-w-[1500px] px-4 py-6 sm:px-6 lg:px-8">
                    <main class="min-w-0 space-y-6">
                        {{ $slot }}
                    </main>
                </div>
            @endauth
        </div>

        @auth
        <div x-show="sidebarOpen" x-cloak
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
            x-transition:enter="transition-opacity duration-200"
            x-transition:leave="transition-opacity duration-200"
            @@click="sidebarOpen = false">
        </div>
        @endauth

        <x-toast />
        @stack('scripts')
        
    </body>
</html>
