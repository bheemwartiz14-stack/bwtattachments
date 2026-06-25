@php
    $user = auth()->user();

    if ($user->hasRole('Super Admin')) {
        $sidebarItems = [
            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['label' => 'Manage WholeSale Client', 'route' => 'admin.wholesale-client-users.index', 'pattern' => 'admin.wholesale-client-users.*', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['label' => 'Categories', 'route' => 'admin.categories.index', 'pattern' => 'admin.categories.*', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
            ['label' => 'Subcategories', 'route' => 'admin.subcategories.index', 'pattern' => 'admin.subcategories.*', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
            ['label' => 'Connections', 'route' => 'admin.connections.index', 'pattern' => 'admin.connections.*', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
            ['label' => 'Products', 'route' => 'admin.products.index', 'pattern' => 'admin.products.*', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label' => 'Product Pricing', 'route' => 'admin.product-pricing.index', 'pattern' => 'admin.product-pricing.*', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
    } elseif ($user->hasRole('Wholesale Client')) {
        $sidebarItems = [
            [
            'label' => 'Dashboard', 
            'route' => 'client.dashboard', 
            'pattern' => 'client.dashboard', 
            'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            [
                'label'   => 'Manage Retailer Users',
                'route'   => 'client.retailer-users.index',
                'pattern' => 'client.retailer-users.*',
                'icon'    => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
            ],
            ['label' => 'Products', 'route' => 'client.products.index', 'pattern' => 'client.products.*', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label' => 'Quotations', 'route' => 'client.quotations.index', 'pattern' => 'client.quotations.*', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ];
    } elseif ($user->hasRole('Retailer')) {
        $sidebarItems = [
            ['label' => 'Dashboard', 'route' => 'retailer.dashboard', 'pattern' => 'retailer.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['label' => 'Products', 'route' => 'retailer.products.index', 'pattern' => 'retailer.products.*', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
            ['label' => 'Quotations', 'route' => 'retailer.quotations.index', 'pattern' => 'retailer.quotations.*', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Profile', 'route' => 'retailer.profile.edit', 'pattern' => 'retailer.profile.*', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        ];
    } else {
        $sidebarItems = [];
    }
@endphp

<aside data-sidebar
    class="hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm no-print dark:border-neutral-800 dark:bg-neutral-950 lg:block lg:sticky lg:top-20 lg:max-h-[calc(100vh-6rem)]">
    <nav class="max-h-[calc(100vh-16rem)] space-y-1 overflow-y-auto pr-1">
        @foreach ($sidebarItems as $item)
            @php
                $isActive = request()->routeIs($item['pattern']);
                $href = Route::has($item['route']) ? route($item['route']) : '#';
            @endphp

            <a href="{{ $href }}"
                class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-slate-900 text-white shadow-sm dark:bg-neutral-800' : 'text-gray-700 hover:bg-slate-50 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-900 dark:hover:text-white' }}">
                <svg class="h-5 w-5 shrink-0 {{ $isActive ? 'text-slate-300 dark:text-neutral-200' : 'text-gray-400 group-hover:text-slate-500 dark:text-neutral-500 dark:group-hover:text-neutral-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="{{ $item['icon'] }}" />
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
</aside>
