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
        <footer class="border-t border-slate-200 bg-white py-5 text-center text-sm text-slate-400 dark:border-neutral-800 dark:bg-black">
            <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
                &copy; BWT | Wholesale B2B Attachment Portal
            </div>
        </footer>
        <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   <script>window.BASE_URL = "{{ url('') }}";</script>
<script>
    (function() {
        var html = document.documentElement;
        var stored = localStorage.getItem('theme');
        console.log('stored',stored);
        document.addEventListener('click', function(e) {
              const toggleBtn = e.target.closest('[data-toggle-dark]');
               if (!toggleBtn) return;
               document.documentElement.classList.toggle('light');
                const isDark = document.documentElement.classList.contains('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    })();
</script>

        @stack('scripts')


    </body>
</html>
