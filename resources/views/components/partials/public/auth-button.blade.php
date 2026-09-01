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
    <div class="{{ isset($mobile) ? 'space-y-2' : 'flex items-center gap-2' }}">
        <a href="{{ route($dashboardRoute) }}" wire:navigate class="{{ $class }}">Dashboard</a>
        @if($showCart)
            @php
                $cartClass = isset($mobile)
                    ? 'flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-colors'
                    : 'relative inline-flex items-center justify-center h-10 w-10 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors border border-white/20';
            @endphp
            <a href="{{ $cartRoute }}" wire:navigate class="{{ $cartClass }}">
                <span class="flex items-center gap-2">
                    <x-ionicon-cart-outline class="h-6 w-6 text-white shrink-0" />
                    @if(isset($mobile))
                        <span class="text-sm font-medium">Cart</span>
                    @endif
                </span>
                <span data-cart-badge @class([
                    'absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full bg-red-500 text-[10px] font-bold leading-none text-white border-2 border-bwtblue',
                    'hidden' => $cartCount === 0,
                    'static ml-2 relative top-0 right-0 border-0' => isset($mobile),
                ])>{{ $cartCount }}</span>
            </a>
        @endif
    </div>
@else
    <a href="{{ route('login') }}" wire:navigate class="{{ $class }}">Reseller Login</a>
@endauth
