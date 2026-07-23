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
    @endphp
    <a href="{{ route($dashboardRoute) }}" wire:navigate class="{{ $class }}">Dashboard</a>
@else
    <a href="{{ route('login') }}" wire:navigate class="{{ $class }}">Reseller Login</a>
@endauth
