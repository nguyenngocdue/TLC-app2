<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="w-full h-full bg-gray-200">
        <div class="flex flex-nowrap">
            <div style="min-height: 700px" class="absolute lg:relative w-64 h-screen shadow bg-gray-100 hidden lg:block">

            </div>
        </div>
    </div>
</body>

</html>