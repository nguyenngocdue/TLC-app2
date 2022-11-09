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
    <script href="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="./windmill_assets/css/tailwind.output.css">
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer=""></script>
    <!-- <script src="./windmill_assets/js/init-alpine1.js"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" defer=""></script>
    <script src="./windmill_assets/js/charts-lines.js" defer=""></script>
    <script src="./windmill_assets/js/charts-pie.js" defer=""></script>
    <style type="text/css">
        /* Chart.js */
        @keyframes chartjs-render-animation {
            from {
                opacity: .99
            }

            to {
                opacity: 1
            }
        }

        .chartjs-render-monitor {
            animation: chartjs-render-animation 1ms
        }

        .chartjs-size-monitor,
        .chartjs-size-monitor-expand,
        .chartjs-size-monitor-shrink {
            position: absolute;
            direction: ltr;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            pointer-events: none;
            visibility: hidden;
            z-index: -1
        }

        .chartjs-size-monitor-expand>div {
            position: absolute;
            width: 1000000px;
            height: 1000000px;
            left: 0;
            top: 0
        }

        .chartjs-size-monitor-shrink>div {
            position: absolute;
            width: 200%;
            height: 200%;
            left: 0;
            top: 0
        }
    </style>
</head>

<body>
    <div class=" flex bg-gray-100 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <x-homepage.sidebar2 />
        <div class="flex flex-col flex-1 w-full">
            <x-homepage.navbar2 />
            <main class="h-full overflow-y-auto">
                <div class="container mx-auto grid px-6">
                    <div class="container flex items-center justify-between h-full mx-auto text-purple-600 dark:text-purple-300">
                        <div class="flex justify-cen1ter flex-1 lg:mr-32">
                            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                                @yield('title', 'Untitled')
                            </h2>
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