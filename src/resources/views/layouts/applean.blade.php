<!DOCTYPE html>
<html :class="{ 'theme-dark': dark } " class="scroll-smooth" x-data="data()" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="The master layout G4T">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('topTitle', 'Applean') - {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('vendor/font-awesome-pro-6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.google.Inter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tlc2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customizeSelect2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    
    <script src="{{ asset('js/alpine.min.js') }}"></script>
    <script src="{{ asset('windmill_assets/js/init-alpine1.js') }}"></script>
    <script src="{{ asset('js/focus-trap.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jsdelivr.net.chart.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/tlc2.js') }}"></script>
    <script src="{{ asset('js/dropdownComponent.js') }}"></script>
    <script src="{{ asset('js/applayout.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
</head>

<body>
    @yield('content')
</body>
applean.blade.php
</html>
