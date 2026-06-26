<meta charset="utf-8">
<script>window.BASE_URL = "{{ url('') }}";</script>
<script>
    (function() {
        var html = document.documentElement;
        var stored = localStorage.getItem('darkMode');
        if (stored === 'true' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('[data-toggle-dark]');
            if (!btn) return;
            html.classList.toggle('dark');
            localStorage.setItem('darkMode', html.classList.contains('dark'));
        });
    })();
</script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ isset($title) ? str_replace('Attachment Portal', config('app.name'), $title) : config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
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