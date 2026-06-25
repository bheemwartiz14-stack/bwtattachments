<meta charset="utf-8">
<script>window.BASE_URL = "{{ url('') }}";</script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ isset($title) ? str_replace('Attachment Portal', config('app.name'), $title) : config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

@php
    $buildDir = config('vite.build_directory', 'build');
    $manifestPath = public_path($buildDir.'/manifest.json');
    $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null;
@endphp

@if (config('vite.enabled') && $manifest)
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@elseif ($manifest)
    <link rel="stylesheet" href="{{ asset($buildDir.'/'.$manifest['resources/css/app.css']['file']) }}">
    <script defer src="{{ asset($buildDir.'/'.$manifest['resources/js/app.js']['file']) }}"></script>
@endif
@stack('styles')