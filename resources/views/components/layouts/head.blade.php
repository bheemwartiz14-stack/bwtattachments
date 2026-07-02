<meta charset="utf-8">

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ isset($title) ? str_replace('BWT', config('app.name'), $title) : config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
<meta name="apple-mobile-web-app-title" content="BWT" />
<link rel="manifest" href="/site.webmanifest" />
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    input:-webkit-autofill,
    input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px white inset !important;
        -webkit-text-fill-color: inherit !important;
        caret-color: inherit !important;
    }
    .dark input:-webkit-autofill,
    .dark input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px #171717 inset !important;
        -webkit-text-fill-color: #d4d4d4 !important;
        caret-color: #d4d4d4 !important;
    }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('styles')
