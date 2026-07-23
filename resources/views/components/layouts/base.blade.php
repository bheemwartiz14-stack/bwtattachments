<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? str_replace('BWT', config('app.name'), $title) : config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/png" sizes="96x96" href="{{ config('app.asset_url') }}/favicon-96x96.png">
    <link rel="icon" type="image/svg+xml" href="{{ config('app.asset_url') }}/favicon.svg">
    <link rel="shortcut icon" href="{{ config('app.asset_url') }}/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ config('app.asset_url') }}/apple-touch-icon.png">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <link rel="manifest" href="{{ config('app.asset_url') }}/site.webmanifest">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:focus,
        textarea:-webkit-autofill,
        textarea:-webkit-autofill:focus,
        select:-webkit-autofill,
        select:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px var(--input-bg) inset !important;
            -webkit-text-fill-color: var(--input-text) !important;
            caret-color: var(--input-text) !important;
            transition: background-color 999999s ease-in-out 0s;
        }
        ol, ul { padding-left: 1.5em; }
        p { margin-bottom: 0.5em; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>

<body {{ $attributes }}>
    {{ $slot }}
    <x-toast />
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/product.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    @livewireScripts
    @stack('scripts')
</body>

</html>
