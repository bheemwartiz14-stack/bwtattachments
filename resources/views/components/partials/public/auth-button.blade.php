@php
    $class = isset($mobile)
        ? 'block px-3 py-2.5 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-colors'
        : 'bg-red-500 hover:bg-red-600 transition-colors text-white font-semibold text-sm px-5 py-2.5 rounded-full no-underline inline-block';
@endphp

@auth
    @php
        $dashboardRoute = match (true) {
            auth()->user()->hasRole('Admin') => 'admin.dashboard',
            auth()->user()->hasRole('Wholesaler') => 'client.dashboard',
            auth()->user()->hasRole('Reseller') => 'reseller.dashboard',
             auth()->user()->hasRole('customer') => 'customer.dashboard',
            default => 'admin.dashboard',
        };
        $cartCount = 0;
        $showCart = false;
        if (auth()->check()) {
            $u = auth()->user();
            $showCart = $u->hasRole('Wholesaler') || $u->hasRole('Reseller') || $u->hasRole('customer');
            if ($showCart) {
                $cartCount = app(\App\Services\UserProductService::class)->getCartCount($u);
            }
        }
        $cartRoute = route('public.cart.index');
    @endphp
    <a href="{{ route($dashboardRoute) }}" wire:navigate class="{{ $class }}">Dashboard</a>
    @if($showCart)
        <a href="{{ $cartRoute }}" wire:navigate class="relative inline-flex items-center justify-center px-3 py-2.5 no-underline">
            <x-ionicon-cart-outline class="h-5 w-5 text-white" />
            <span data-cart-badge @class([
                'absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-red-500 text-[10px] font-bold leading-none text-white',
                'hidden' => $cartCount === 0,
            ])>{{ $cartCount }}</span>
        </a>
    @endif
@else
    <a href="{{ route('login') }}" wire:navigate class="{{ $class }}">Reseller Login</a>
@endauth
