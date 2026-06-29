<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? str_replace('Attachment Portal', config('app.name'), $title) : config('app.name') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:Arial,Helvetica,sans-serif;}
        body{background:#f4f4f4;color:#333;}

        header{background:#1d2939;color:white;padding:15px 40px;display:flex;justify-content:space-between;align-items:center;}
        .logo{font-size:24px;font-weight:bold;}
        .logo a{color:white;text-decoration:none;}
        nav a{color:white;text-decoration:none;margin:0 12px;}
        .login-btn{background:#f59e0b;color:white;padding:10px 20px;border:none;border-radius:5px;cursor:pointer;text-decoration:none;font-size:14px;}

        .hero{background:white;padding:50px;text-align:center;border-bottom:1px solid #ddd;}
        .hero h1{font-size:38px;margin-bottom:15px;}
        .hero p{font-size:18px;color:#666;}

        .container{display:flex;padding:25px;gap:25px;}
        .sidebar{width:280px;background:white;padding:20px;border-radius:8px;flex-shrink:0;}
        .sidebar h3{margin-bottom:15px;}
        .filter-group{margin-bottom:25px;}
        .filter-group label{display:block;margin-bottom:8px;}
        .content{flex:1;min-width:0;}

        .searchbar{display:flex;margin-bottom:20px;}
        .searchbar input{flex:1;padding:12px;border:1px solid #ccc;border-right:none;font-size:14px;}
        .searchbar input:focus{outline:none;border-color:#1d2939;}
        .searchbar button{padding:12px 20px;background:#1d2939;color:white;border:none;cursor:pointer;font-size:14px;}
        .searchbar .clear-btn{padding:12px 20px;background:#e74c3c;color:white;border:none;cursor:pointer;font-size:14px;text-decoration:none;display:inline-flex;align-items:center;}

        .products{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;}
        .product-card{background:white;border-radius:8px;overflow:hidden;box-shadow:0 2px 5px rgba(0,0,0,.1);text-decoration:none;color:inherit;display:block;transition:box-shadow .2s;}
        .product-card:hover{box-shadow:0 4px 12px rgba(0,0,0,.15);}
        .product-image{aspect-ratio:3/2;background:#d9d9d9;display:flex;align-items:center;justify-content:center;font-size:20px;color:#666;overflow:hidden;}
        .product-image img{width:100%;height:100%;object-fit:cover;}
        .product-body{padding:18px;}
        .product-code{font-weight:bold;color:#1d2939;margin-bottom:8px;font-size:14px;}
        .specs{margin-top:12px;font-size:14px;}
        .specs div{margin-bottom:5px;color:#555;}
        .product-buttons{margin-top:15px;}
        .product-buttons button,.view-btn{width:100%;padding:10px;border:none;border-radius:5px;cursor:pointer;font-size:14px;}
        .view-btn{background:#1d2939;color:white;text-align:center;display:block;text-decoration:none;}

        .detail-page{background:white;padding:30px;border-radius:8px;margin-top:20px;}
        .detail-layout{display:flex;gap:40px;}
        .gallery{width:45%;flex-shrink:0;}
        .main-image{aspect-ratio:3/2;background:#d9d9d9;display:flex;align-items:center;justify-content:center;overflow:hidden;border-radius:8px;}
        .main-image img{width:100%;height:100%;object-fit:cover;}
        .thumbs{display:flex;gap:10px;margin-top:10px;}
        .thumb{flex:1;height:80px;background:#d9d9d9;border-radius:4px;overflow:hidden;cursor:pointer;border:2px solid transparent;transition:border-color .2s;}
        .thumb.active{border-color:#1d2939;}
        .thumb img{width:100%;height:100%;object-fit:cover;}
        .product-info{flex:1;}

        .product-info h2{margin-bottom:15px;font-size:24px;}
        .product-info .desc{margin-bottom:15px;color:#555;line-height:1.6;}
        .spec-table{width:100%;margin-top:20px;border-collapse:collapse;}
        .spec-table td{padding:10px;border-bottom:1px solid #eee;font-size:14px;}
        .spec-table td:first-child{font-weight:600;color:#1d2939;width:40%;}
        .client-box{margin-top:25px;background:#eef6ff;padding:20px;border-radius:8px;}
        .client-box h3{margin-bottom:10px;font-size:16px;}
        .price{font-size:28px;color:#008000;font-weight:bold;margin:15px 0;}
        .download-btn{background:#0f766e;color:white;padding:12px 20px;border:none;border-radius:5px;cursor:pointer;font-size:14px;text-decoration:none;display:inline-block;margin-right:10px;}
        .quote-btn{background:#f59e0b;color:white;padding:12px 20px;border:none;border-radius:5px;cursor:pointer;font-size:14px;text-decoration:none;display:inline-block;}

        .category-tag{display:inline-block;background:#eef2ff;color:#1d2939;padding:4px 10px;border-radius:4px;font-size:12px;margin-right:5px;margin-top:8px;}

        footer{margin-top:50px;background:#1d2939;color:white;text-align:center;padding:25px;font-size:14px;}

        .pagination{display:flex;justify-content:center;gap:5px;margin-top:30px;}
        .pagination a,.pagination span{padding:8px 14px;background:white;border:1px solid #ddd;border-radius:4px;text-decoration:none;color:#333;font-size:14px;}
        .pagination a:hover{background:#f0f0f0;}
        .pagination .active{background:#1d2939;color:white;border-color:#1d2939;}

        select.filter-select{width:100%;padding:10px;border:1px solid #ddd;border-radius:4px;font-size:14px;margin-bottom:15px;}

        @media(max-width:768px){
            header{padding:15px 20px;flex-wrap:wrap;gap:10px;}
            .container{flex-direction:column;padding:15px;}
            .sidebar{width:100%;}
            .detail-layout{flex-direction:column;}
            .gallery{width:100%;}
            .hero{padding:30px 20px;}
            .hero h1{font-size:28px;}
            .products{grid-template-columns:1fr;}
        }
    </style>
    @stack('styles')
</head>
<body>

<header>
    <div class="logo"><a href="{{ url('/') }}">{{ config('app.name', 'BIG WORK TOOLS') }}</a></div>
    <nav>
        <a href="{{ url('/') }}">Home</a>
        @foreach(\App\Models\Category::orderBy('name')->get() as $cat)
            <a href="{{ route('public.categories.show', $cat->id) }}">{{ $cat->name }}</a>
        @endforeach
    </nav>
    @auth
        @php
            $dashboardRoute = match(true) {
                auth()->user()->hasRole('Super Admin') => 'admin.dashboard',
                auth()->user()->hasRole('Wholesale Client') => 'client.dashboard',
                auth()->user()->hasRole('Retailer') => 'retailer.dashboard',
                default => 'admin.dashboard',
            };
        @endphp
        <a href="{{ route($dashboardRoute) }}" class="login-btn">Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="login-btn">Client Login</a>
    @endauth
</header>

{{ $slot }}

<footer>
    &copy; Big Work Tools | Private B2B Attachment Portal
</footer>

</body>
</html>
