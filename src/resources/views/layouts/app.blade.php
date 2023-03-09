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
    <link rel="stylesheet" href="{{ asset('css/flowbite.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.google.Inter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customizeSelect2.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datepicker.css') }}">
    {{-- TLC2 will always be the last of CSS List --}}
    <link rel="stylesheet" href="{{ asset('css/tlc2.css') }}">
        
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
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
    <script src="{{ asset('js/datepicker.min.js') }}"></script>
    <script src="{{ asset('js/dropdownComponent.js') }}"></script>
    <script src="{{ asset('js/components/Dropdown2.js') }}"></script>
    <script src="{{ asset('js/components/Dropdown4.js') }}"></script>
    <script src="{{ asset('js/components/EditableTable2.js') }}"></script>
    <script src="{{ asset('js/components/EditableTableAddNewLine2.js') }}"></script>
    <script src="{{ asset('js/components/Number4.js') }}"></script>
    {{-- <script src="{{ asset('js/components/EditableTable.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/components/EditableTableAddNewLine.js') }}"></script> --}}
    <script src="{{ asset('js/components/SearchModal.js') }}"></script>
    <script src="{{ asset('js/components/KeyArrowTable.js') }}"></script>
    <script src="{{ asset('js/applayout.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body >
    <div  class="bg-gray-100 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        {{-- <x-homepage.sidebar2 /> --}}
        <div class="flex flex-col flex-1 w-full">
            <x-homepage.navbar2 />
            @auth
            <main class="mt-16 mb-8 w-full flex-grow bg-green-400 dark:bg-gray-700 no-print">
                <div class="w-full h-18">
                    <div class="no-print flex bg-white dark:bg-gray-800 flex-wrap items-center justify-between h-full text-purple-600 dark:text-purple-300">
                        <div class="w-full lg:w-1/2 lg:px-6 sm:px-2 md:px-4 flex">
                            <x-renderer.heading level=4>
                                @yield('title', 'Untitled')
                            </x-renderer.heading>
                            @if(isset($type))
                            <x-renderer.heading-status :type="$type">
                                @yield('status', 'Untitled')
                            </x-renderer.heading-status>
                            @endif
                        </div>
                        <ul class="w-full lg:w-1/2">
                            <li class="relative">
                                <x-navigation.breadcrumb />
                            </li>
                        </ul>
                    </div>
                </div>
            </main>
            @endauth
            @guest
                <div class="mt-16 mb-8 no-print">
                </div>
            @endguest
            <div id="print-pdf-document"  class="w-full min-h-screen MUST-NOT-HAVE-X-PADDING-MARGIN-FOR-PRINT-PAGE">
                @yield('content')
            </div>
            <div class="mt-8 no-print"></div>
        </div>
    </div>
        {!! Toastr::message() !!}
</body>

</html>
