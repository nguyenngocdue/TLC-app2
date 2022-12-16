<!DOCTYPE html>
<html :class="{ 'theme-dark': dark } " class="scroll-smooth" x-data="data()" lang="en">

<!--head>
    <meta charset="utf-8">
    <meta name="The master layout G4T">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('css/tlc2.css') }}">
    <script href="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('css/tlc2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customizeSelect2.css') }}">
    <script href="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="./windmill_assets/css/tailwind.output.css">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer=""></script>
    <!-- <script src="./windmill_assets/js/init-alpine1.js"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" defer=""></script>
    <script src="./windmill_assets/js/charts-lines.js" defer=""></script>
    <script src="./windmill_assets/js/charts-pie.js" defer=""></script>

    <!-- <link href="{{ asset('css/charts.css') }}" rel="stylesheet"> -->

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    <div class=" flex bg-gray-100 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <x-homepage.sidebar2 />
        <div class="flex flex-col flex-1 w-full">
            <x-homepage.navbar2 />
            <main class="h-full overflow-y-auto">
                <div class="container1 mx-auto grid px-6">
                    <div class="container1 flex items-center justify-between h-full mx-auto1 text-purple-600 dark:text-purple-300">
                        <div class="flex justify-cen1ter flex-1 lg:mr-32">
                            <x-renderer.heading level=3>@yield('title', 'Untitled')</x-renderer.heading>
                        </div>
                        <ul class="flex items-center flex-shrink-0 space-x-6">
                            <li class="relative">
                                <x-controls.breadcrumb />
                            </li>
                        </ul>
                    </div>
                    @yield('content')
                </div>
            </main>
        </div>

        <script type="text/javascript" src="{{ asset('js/tlc.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/applayout.js') }}"></script>
        <script src="{{ asset('/windmill_assets/js/init-alpine1.js')}}"></script>
        <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {!! Toastr::message() !!}
</body>

</html>
