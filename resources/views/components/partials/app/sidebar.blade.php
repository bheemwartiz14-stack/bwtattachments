@php
    $user = auth()->user();

    $iconMap = [
        'Dashboard' => 'heroicon-o-home',
        'Manage WholeSale Client' => 'heroicon-o-users',
        'Manage Retailer Users' => 'heroicon-o-user-group',
        'Manage Customer Users' => 'heroicon-o-users',
        'Categories' => 'heroicon-o-rectangle-stack',
        'Subcategories' => 'heroicon-o-tag',
        'Connections' => 'heroicon-o-link',
        'Products' => 'heroicon-o-cube',
        'Contact Messages' => 'heroicon-o-envelope',
        'Site Settings' => 'heroicon-o-cog-6-tooth',
        'Quotations' => 'heroicon-o-document-text',
        'Profile' => 'heroicon-o-user',
    ];

    $rolePrefix = match (true) {
        $user->hasRole('Super Admin') => 'admin',
        $user->hasRole('Wholesale Client') => 'client',
        $user->hasRole('Retailer') => 'retailer',
        $user->hasRole('customer') => 'customer',
        default => null,
    };

    if ($user->hasRole('Super Admin')) {
        $sidebarItems = [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard'],
            [
                'label' => 'Manage WholeSale Client',
                'route' => 'admin.wholesale-client-users.index',
                'pattern' => 'admin.wholesale-client-users.*',
            ],
            ['label' => 'Categories', 'route' => 'admin.categories.index', 'pattern' => 'admin.categories.*'],
            ['label' => 'Subcategories', 'route' => 'admin.subcategories.index', 'pattern' => 'admin.subcategories.*'],
            ['label' => 'Connections', 'route' => 'admin.connections.index', 'pattern' => 'admin.connections.*'],
            ['label' => 'Products', 'route' => 'admin.products.index', 'pattern' => 'admin.products.*'],
            ['label' => 'Site Settings', 'route' => 'admin.setting.genral-setting', 'pattern' => 'admin.setting.*'],
        ];
    } elseif ($user->hasRole('Wholesale Client')) {
        $sidebarItems = [
            ['label' => 'Dashboard', 'route' => 'client.dashboard', 'pattern' => 'client.dashboard'],
            [
                'label' => 'Manage Retailer Users',
                'route' => 'client.retailer-users.index',
                'pattern' => 'client.retailer-users.*',
            ],
            ['label' => 'Products', 'route' => 'client.products.index', 'pattern' => 'client.products.*'],
            ['label' => 'Quotations', 'route' => 'client.quotations.index', 'pattern' => 'client.quotations.*'],
        ];
    } elseif ($user->hasRole('Retailer')) {
        $sidebarItems = [
            ['label' => 'Dashboard', 'route' => 'retailer.dashboard', 'pattern' => 'retailer.dashboard'],
            [
                'label' => 'Manage Customer Users',
                'route' => 'retailer.customer-users.index',
                'pattern' => 'retailer.customer-users.*',
            ],
            ['label' => 'Products', 'route' => 'retailer.products.index', 'pattern' => 'retailer.products.*'],
            ['label' => 'Quotations', 'route' => 'retailer.quotations.index', 'pattern' => 'retailer.quotations.*'],
        ];
    } elseif ($user->hasRole('customer')) {
        $sidebarItems = [
            ['label' => 'Dashboard', 'route' => 'customer.dashboard', 'pattern' => 'customer.dashboard'],
            ['label' => 'Products', 'route' => 'customer.products.index', 'pattern' => 'customer.products.*'],
        ];
    } else {
        $sidebarItems = [];
    }
@endphp

<aside data-sidebar
    class="hidden rounded-2xl border border-slate-200 bg-white p-3 shadow-sm no-print dark:border-neutral-800 dark:bg-neutral-950 lg:block lg:sticky lg:top-20 lg:max-h-[calc(100vh-6rem)]">
    <nav class="max-h-[calc(100vh-14rem)] space-y-0.5 overflow-y-auto pr-1">
        @foreach ($sidebarItems as $item)
            @php
                $hasChildren = isset($item['children']) && count($item['children']);
                $isGroupActive =
                    $hasChildren && collect($item['children'])->contains(fn($c) => request()->routeIs($c['pattern']));
                $icon = $item['icon'] ?? ($iconMap[$item['label']] ?? 'heroicon-o-ellipsis-horizontal');
            @endphp

            @if ($hasChildren)
                <div x-data="{ open: @json($isGroupActive) }" class="pt-1">
                    <button @click="open = !open"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $isGroupActive ? 'text-emerald-700 dark:text-emerald-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                        <span class="flex h-5 w-5 items-center justify-center">
                            @svg($icon, 'h-5 w-5')
                        </span>
                        <span class="flex-1 text-left">{{ $item['label'] }}</span>
                        <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse.duration.200ms>
                        <div class="ml-4 mt-0.5 space-y-0.5 border-l-2 border-slate-200 pl-2 dark:border-neutral-700">
                            @foreach ($item['children'] as $child)
                                @php
                                    $isChildActive = request()->routeIs($child['pattern']);
                                    $href = Route::has($child['route']) ? route($child['route']) : '#';
                                @endphp
                                <a href="{{ $href }}"
                                    class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-all {{ $isChildActive ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                                    <span
                                        class="h-1.5 w-1.5 rounded-full {{ $isChildActive ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-neutral-600' }}"></span>
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                @php
                    $isActive = request()->routeIs($item['pattern']);
                    $href = Route::has($item['route']) ? route($item['route']) : '#';
                @endphp
                <a href="{{ $href }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all {{ $isActive ? 'bg-emerald-50 text-emerald-700 shadow-sm dark:bg-emerald-900/20 dark:text-emerald-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                    <span class="flex h-5 w-5 items-center justify-center">
                        @svg($icon, 'h-5 w-5')
                    </span>
                    <span class="flex-1">{{ $item['label'] }}</span>
                    @if ($isActive)
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>
</aside>
