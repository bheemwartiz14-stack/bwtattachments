<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BWT | Wholesale Attachment Product Database' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ app(\App\Settings\GeneralSettings::class)->logo_favicon ? asset(app(\App\Settings\GeneralSettings::class)->logo_favicon) : asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

  <header class="bg-bwtblue text-white">
    <div class="max-w-[1700px] mx-auto flex items-center justify-between px-8 py-4">
      <div class="flex items-center gap-2">
        <a href="{{ url('/') }}" class="text-3xl font-extrabold tracking-tight border-2 border-white px-2 py-0.5 text-white no-underline">BWT</a>
      </div>

      <nav class="hidden lg:flex items-center gap-8 text-[15px] font-medium">
        @foreach(\App\Models\Category::orderBy('name')->get() as $cat)
          <a href="{{ route('public.categories.show', $cat) }}" class="hover:text-blue-200">{{ $cat->name }}</a>
        @endforeach
        <a href="{{ route('public.contact.index') }}" class="hover:text-blue-200">Contact</a>
      </nav>

      <div>
        @auth
          @php
            $dashboardRoute = match(true) {
                auth()->user()->hasRole('Super Admin') => 'admin.dashboard',
                auth()->user()->hasRole('Wholesale Client') => 'client.dashboard',
                auth()->user()->hasRole('Retailer') => 'retailer.dashboard',
                default => 'admin.dashboard',
            };
          @endphp
          <a href="{{ route($dashboardRoute) }}" class="bg-red-500 hover:bg-red-600 transition-colors text-white font-semibold text-sm px-5 py-2.5 rounded-full no-underline inline-block">
            Dashboard
          </a>
        @else
          <a href="{{ route('login') }}" class="bg-red-500 hover:bg-red-600 transition-colors text-white font-semibold text-sm px-5 py-2.5 rounded-full no-underline inline-block">
            Reseller Login
          </a>
        @endauth
      </div>
    </div>
  </header>

  {{ $slot }}

  <footer class="bg-black text-white text-center py-5 text-sm">
    &copy; BWT | Wholesale B2B Attachment Portal
  </footer>


  @livewireScripts
</body>
</html>
