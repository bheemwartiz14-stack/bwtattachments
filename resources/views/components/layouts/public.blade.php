<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BWT | Wholesale Attachment Product Database' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased" x-data="{ mobileMenu: false }">

  <header class="bg-bwtblue text-white">
    <div class="max-w-[1700px] mx-auto flex items-center justify-between px-4 sm:px-8 py-4">
      <div class="flex items-center gap-2">
        <a href="{{ url('/') }}" class="no-underline"><img src="{{ asset('images/bwt-logo.png') }}" alt="BWT" class="h-12 sm:h-14 w-auto"></a>
      </div>

      <nav class="hidden lg:flex items-center gap-8 text-[15px] font-medium">
        @php
          $cats = \App\Models\Category::whereIn('name', ['Excavator Attachments', 'Wheel Loader Attachments', 'Wear Parts', 'Spare Parts'])->get()->keyBy('name');
        @endphp
        @if($cats->has('Excavator Attachments'))
          <a href="{{ route('public.categories.show', $cats['Excavator Attachments']) }}" class="hover:text-blue-200">Excavator Attachments</a>
        @endif
        @if($cats->has('Wheel Loader Attachments'))
          <a href="{{ route('public.categories.show', $cats['Wheel Loader Attachments']) }}" class="hover:text-blue-200">Wheel Loader Attachments</a>
        @endif
        @if($cats->has('Wear Parts'))
          <a href="{{ route('public.categories.show', $cats['Wear Parts']) }}" class="hover:text-blue-200">Wear Parts</a>
        @endif
        @if($cats->has('Spare Parts'))
          <a href="{{ route('public.categories.show', $cats['Spare Parts']) }}" class="hover:text-blue-200">Spare Parts</a>
        @endif
        <a href="{{ route('public.reseller-program.index') }}" class="hover:text-blue-200">Reseller program</a>
        <a href="{{ route('public.contact.index') }}" class="hover:text-blue-200">Contact</a>
      </nav>

      <div class="flex items-center gap-3">
        <div class="hidden sm:block">
          @auth
            @php
              $dashboardRoute = match(true) {
                  auth()->user()->hasRole('Super Admin') => 'admin.dashboard',
                  auth()->user()->hasRole('Wholesale Client') => 'client.dashboard',
                  auth()->user()->hasRole('Retailer') => 'retailer.dashboard',
                  default => 'admin.dashboard',
              };
            @endphp
            <a href="{{ route($dashboardRoute) }}" class="bg-red-500 hover:bg-red-600 transition-colors text-white font-semibold text-sm px-5 py-2.5 rounded-full no-underline inline-block">Dashboard</a>
          @else
            <a href="{{ route('login') }}" class="bg-red-500 hover:bg-red-600 transition-colors text-white font-semibold text-sm px-5 py-2.5 rounded-full no-underline inline-block">Reseller Login</a>
          @endauth
        </div>

        <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 text-white hover:text-blue-200 focus:outline-none">
          <svg x-show="!mobileMenu" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
          <svg x-show="mobileMenu" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
    </div>

    <div x-show="mobileMenu" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" @click.away="mobileMenu = false" class="lg:hidden border-t border-white/20">
      <div class="px-4 py-4 space-y-1">
        @if($cats->has('Excavator Attachments'))
          <a href="{{ route('public.categories.show', $cats['Excavator Attachments']) }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-colors">Excavator Attachments</a>
        @endif
        @if($cats->has('Wheel Loader Attachments'))
          <a href="{{ route('public.categories.show', $cats['Wheel Loader Attachments']) }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-colors">Wheel Loader Attachments</a>
        @endif
        @if($cats->has('Wear Parts'))
          <a href="{{ route('public.categories.show', $cats['Wear Parts']) }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-colors">Wear Parts</a>
        @endif
        @if($cats->has('Spare Parts'))
          <a href="{{ route('public.categories.show', $cats['Spare Parts']) }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-colors">Spare Parts</a>
        @endif
        <a href="{{ route('public.reseller-program.index') }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-colors">Reseller program</a>
        <a href="{{ route('public.contact.index') }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-colors">Contact</a>
        <hr class="border-white/20 my-2">
        @auth
          @php
            $dashboardRoute = match(true) {
                auth()->user()->hasRole('Super Admin') => 'admin.dashboard',
                auth()->user()->hasRole('Wholesale Client') => 'client.dashboard',
                auth()->user()->hasRole('Retailer') => 'retailer.dashboard',
                default => 'admin.dashboard',
            };
          @endphp
          <a href="{{ route($dashboardRoute) }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-colors">Dashboard</a>
        @else
          <a href="{{ route('login') }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-white hover:bg-white/10 transition-colors">Reseller Login</a>
        @endauth
      </div>
    </div>
  </header>

  {{ $slot }}

  <footer class="bg-black text-white text-center py-5 text-sm">
    &copy; All rights reserved | BWT Attachments
  </footer>

  @livewireScripts
</body>
</html>
