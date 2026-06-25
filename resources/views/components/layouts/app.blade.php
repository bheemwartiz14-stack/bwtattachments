<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('components.layouts.head')
    </head>

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-white dark:bg-black">
            @auth
                @include('components.layouts.navigation')
            @endauth

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
        <div id="sidebar-backdrop"
            class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden hidden"
            data-sidebar-backdrop>
        </div>
        @endauth

        <x-toast />
        <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <script>
            window.BASE_URL = "{{ url('/') }}";
            </script>
        @stack('scripts')

    </body>
</html>
