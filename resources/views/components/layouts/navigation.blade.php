@php
    $user = auth()->user();
    $rolePrefix = match(true) {
        $user->hasRole('Super Admin') => 'admin',
        $user->hasRole('Wholesale Client') => 'client',
        $user->hasRole('Retailer') => 'retailer',
        default => 'client',
    };
    $dashboardRoute = route($rolePrefix . '.dashboard');
    $profileRoute = route($rolePrefix . '.profile.edit');
@endphp

<nav class="sticky top-0 z-40 border-b border-slate-100/80 bg-white/95 shadow-sm backdrop-blur no-print dark:border-neutral-800/80 dark:bg-neutral-950/95">
    <div class="mx-auto max-w-[1500px] px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button data-toggle-sidebar class="flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-slate-100 dark:text-neutral-400 dark:hover:bg-neutral-900 lg:hidden">
                    @svg('heroicon-o-bars-3', 'h-5 w-5')
                </button>

                <a href="{{ $dashboardRoute }}" class="flex shrink-0 items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center overflow-hidden rounded-xl bg-emerald-600 shadow-sm ring-1 ring-slate-200 dark:ring-neutral-700">
                        @svg('app-logo', 'h-6 w-6 text-white')
                    </span>
                    <span class="hidden sm:block">
                        <span class="block text-sm font-bold uppercase tracking-wide text-slate-950 dark:text-white">Attachment</span>
                        <span class="block text-xs font-medium text-gray-500 dark:text-neutral-400">Operations Portal</span>
                    </span>
                </a>
            </div>

            <div class="flex items-center gap-2">
                <button data-toggle-dark class="flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-slate-100 dark:text-neutral-400 dark:hover:bg-neutral-900">
                    @svg('heroicon-o-moon', 'h-5 w-5')
                </button>

                <div class="relative">
                    <button type="button"
                        data-dropdown-toggle="user-menu"
                        class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-white py-1.5 pe-2 ps-1.5 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                        <span class="grid h-9 w-9 place-items-center overflow-hidden rounded-xl">
                            {!! Avatar::create($user->name)->setDimension(36)->setFontSize(16)->toSvg() !!}
                        </span>
                        <span class="hidden text-left md:block">
                            <span class="block max-w-36 truncate text-sm font-bold text-slate-950 dark:text-white">{{ $user->name }}</span>
                            <span class="block max-w-36 truncate text-xs text-gray-500 dark:text-neutral-400">{{ $user->email }}</span>
                        </span>
                        @svg('heroicon-o-chevron-down', 'h-4 w-4 text-gray-400')
                    </button>
                    <div id="user-menu"
                        data-dropdown-menu
                        class="hidden absolute right-0 mt-2 w-56 rounded-xl border border-slate-100 bg-white shadow-lg dark:border-neutral-800 dark:bg-neutral-950">
                        <div class="px-4 py-3">
                            <p class="truncate text-sm font-bold text-slate-950 dark:text-white">{{ $user->name }}</p>
                            <p class="truncate text-xs text-gray-500 dark:text-neutral-400">{{ $user->email }}</p>
                        </div>
                        <div class="border-t border-slate-100 dark:border-neutral-800"></div>
                        <a href="{{ $profileRoute }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 dark:text-neutral-300 dark:hover:bg-neutral-900">Profile Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
