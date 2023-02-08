<!DOCTYPE html>
<script>
    function check() {
        if (window.localStorage.getItem('dark')) {
            return JSON.parse(window.localStorage.getItem('dark'))
        }
        return false;
    }
    const isDark =  check();
    if(isDark){
        var root = document.getElementsByTagName('html')[0];
        root.classList.add('dark');
    }
</script>
<html :class="{ 'dark': dark }" x-data="data()" lang="en">

<head>
    <script src="{{ asset('windmill_assets/js/init-alpine1.js') }}"></script>
    <script src="{{ asset('js/alpine.min.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="The master layout G4T">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('topTitle', 'Untitled') - @yield('title', 'Untitled') - {{ config('app.name') }}</title>
    
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome-pro-6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.google.Inter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tlc2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customizeSelect2.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css')}}" />
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
        
    <script src="{{ asset('js/focus-trap.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('AdminLTE/plugins/moment/moment.min.js')}}"></script>
    <script src="{{ asset('AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <script src="{{ asset('js/jsdelivr.net.chart.js') }}"></script>
    <script src="{{ asset('js/tlc2.js') }}"></script>
    <script src="{{ asset('js/dropdownComponent.js') }}"></script>
    <script src="{{ asset('js/components/Dropdown2.js') }}"></script>
    <script src="{{ asset('js/components/SearchModal.js') }}"></script>
    <script src="{{ asset('js/applayout.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
</head>
<body >
    <div class="bg-gray-100 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        {{-- <x-homepage.sidebar2 /> --}}
        <div class="flex flex-col w-full">
            <x-homepage.navbar2 />
            <main class="w-full flex-grow bg-gray-100 dark:bg-gray-700 min-h-screen">
                <div class="w-full lg:px-6 sm:px-2 md:px-4">
                    <div class="no-print flex flex-wrap items-center justify-between h-full text-purple-600 dark:text-purple-300">
                        <div class="w-full lg:w-1/2">
                            <x-renderer.heading level=3>@yield('title', 'Untitled')</x-renderer.heading>
                        </div>
                        <ul class="w-full lg:w-1/2">
                            <li class="relative">
                                <x-navigation.breadcrumb />
                            </li>
                        </ul>
                    </div>
                    <div class="only-print">
                        TLC LOGO HERE
                    </div>
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
        {!! Toastr::message() !!}
</body>

</html>
