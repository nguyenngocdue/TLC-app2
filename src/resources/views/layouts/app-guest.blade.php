<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <link rel="stylesheet" href="{{ asset('fonts/ClashGrotesk/WEB/css/clash-grotesk.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome-pro-6/css/all.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet">
    <link href="{{ asset('css/flowbite.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
    {{-- <script src="{{ asset('js/flowbite.min.js') }}"></script> --}}
    <script src="{{ asset('js/alpine.min.js') }}"></script>

</head>

<style>
    body {font-family: "ClashGrotesk-Regular", sans-serif;}
    .text-yellow-tlc {color: #da9f5c ;}
    .text-black-tlc {color: #161c2d ;}
    .bg-black-tlc {background-color: #161c2d;}
    .bg-blue-tlc {background-color: #2f4273;}

</style>

<body>
    @yield('content')
</body>
</html>
