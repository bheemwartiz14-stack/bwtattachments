@php
    $user = auth()->user();
    $rolePrefix = match (true) {
        $user->hasRole('Super Admin') => 'admin',
        $user->hasRole('Wholesale Client') => 'client',
        $user->hasRole('Retailer') => 'retailer',
        $user->hasRole('customer') => 'customer',
        default => 'client',
    };
    $dashboardRoute = route($rolePrefix . '.dashboard');
    $profileRoute = route($rolePrefix . '.profile.edit');
@endphp

<nav
    class="sticky top-0 z-40 border-b border-slate-200 bg-white shadow-sm no-print dark:border-neutral-800 dark:bg-neutral-950">
    <div class="mx-auto max-w-[1500px] px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <button data-toggle-sidebar
                    class="flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-slate-100 dark:text-neutral-400 dark:hover:bg-neutral-900 lg:hidden">
                    @svg('heroicon-o-bars-3', 'h-5 w-5')
                </button>

                <a href="{{ $dashboardRoute }}" wire:navigate class="flex items-center gap-3">
                    <img src="{{ asset('images/bwt-logo.jpg') }}" alt="BWT Logo" class="h-10 w-13 object-contain">

                </a>
            </div>

            <div class="flex items-center gap-2">
                <button data-toggle-dark
                    class="flex items-center justify-center rounded-lg p-2 text-gray-500 hover:bg-slate-100 dark:text-neutral-400 dark:hover:bg-neutral-900">
                    <span class="dark:hidden">@svg('heroicon-o-sun', 'h-5 w-5')</span>
                    <span class="hidden dark:block">@svg('heroicon-o-moon', 'h-5 w-5')</span>
                </button>

                <div class="relative">
                    <button type="button" data-dropdown-toggle="user-menu"
                        class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white py-1.5 pe-2.5 ps-1.5 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:border-neutral-800 dark:bg-neutral-900 dark:hover:bg-neutral-800">
                        <span
                            class="grid h-8 w-8 place-items-center overflow-hidden rounded-lg bg-gradient-to-br from-slate-100 to-slate-200 dark:from-neutral-800 dark:to-neutral-700">
                            @php $avatar = $user->getFirstMedia('avatar'); @endphp
                            @if ($avatar)
                                <img src="{{ $avatar->getUrl() }}" alt="" class="h-full w-full object-cover">
                            @else
                                {!! Avatar::create($user->name)->setDimension(32)->setFontSize(14)->toSvg() !!}
                            @endif
                        </span>
                        <span class="hidden text-left md:block">
                            <span
                                class="block max-w-32 truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $user->name }}</span>
                            <span
                                class="block max-w-32 truncate text-xs text-slate-500 dark:text-neutral-400">{{ $user->email }}</span>
                        </span>
                        @svg('heroicon-o-chevron-down', 'h-4 w-4 text-slate-400')
                    </button>
                    <div id="user-menu" data-dropdown-menu
                        class="hidden absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl dark:border-neutral-800 dark:bg-neutral-950">
                        <div class="border-b border-slate-100 px-3 pb-2 mb-1 dark:border-neutral-800">
                            <p class="truncate text-sm font-semibold text-slate-900 dark:text-white">{{ $user->name }}
                            </p>
                            <p class="truncate text-xs text-slate-500 dark:text-neutral-400">{{ $user->email }}</p>
                        </div>
                        <a href="{{ $profileRoute }}" wire:navigate
                            class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-slate-700 transition-colors hover:bg-slate-50 dark:text-neutral-300 dark:hover:bg-neutral-900">
                            @svg('heroicon-o-cog-6-tooth', 'h-4 w-4')
                            Profile Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                                @svg('heroicon-o-arrow-right-on-rectangle', 'h-4 w-4')
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
