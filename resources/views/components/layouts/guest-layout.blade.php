<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('components.layouts.head')
    </head>
    <body class="font-sans antialiased">
        {{ $slot }}
    </body>
</html>