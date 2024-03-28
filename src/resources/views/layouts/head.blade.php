<head>
    <script src="{{ asset('windmill_assets/js/init-alpine20240320.js') }}"></script>
    <script src="{{ asset('js/alpine.min.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="The master layout G4T">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('topTitle', 'Untitled') - @yield('title', 'Untitled') - {{ config('app.name') }}</title>
    
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome-pro-6/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flowbite.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.google.Inter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('css/datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">

    

    {{-- TLC2 will always be the last of CSS List --}}
    <link rel="stylesheet" href="{{ asset('css/select2-canh.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-due.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tlc2-20231128.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/fullcalendar.bundle.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/plugin.bundle.css') }}"> 

    <link rel="stylesheet" href="{{ asset('css/justifiedGallery@3.8.1.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/lightgallery.css') }}">
        
    <script src="{{ asset('js/focus-trap.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/number-to-words.js') }}"></script>
    <script src="{{ asset('js/lazysizes.js') }}"></script>
    <script src="{{ asset('js/flatpickr@4.6.13.min.js') }}"></script>
    
    <script src="{{ asset('AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('AdminLTE/plugins/moment/moment.min.js')}}"></script>
    <script src="{{ asset('AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <script src="{{ asset('js/jsdelivr.net.chart.js') }}"></script>

    <script src="{{ asset('js/tlc2-20240319.js') }}"></script>
    <script src="{{ asset('js/parseNumber-20240312.js') }}"></script>
    <script src="{{ asset('js/textarea-diff-20240312.js') }}"></script>
    <script src="{{ asset('js/listenSubmitForm-20240312.js') }}"></script>
    
    <script src="{{ asset('js/flowbite.min.js') }}"></script>
    <script src="{{ asset('js/datepicker.min.js') }}"></script>
    <script src="{{ asset('js/dropdownComponent.js') }}"></script>
    <script src="{{ asset('js/chartComponent-20231110.js') }}"></script>
    <script src="{{ asset('js/components/ActionMultiple.js') }}"></script>
    
    <script src="{{ asset('js/components/RadioOrCheckbox2-20230731.js') }}"></script>
    <script src="{{ asset('js/components/Number4.js') }}"></script>
    <script src="{{ asset('js/components/Dropdown2-20231219.js') }}"></script>
    <script src="{{ asset('js/components/Dropdown4-20240327.js') }}"></script>

    <script src="{{ asset('js/components/EditableTable2-20231229.js') }}"></script>
    <script src="{{ asset('js/components/EditableTableAddNewLine2-20230102.js') }}"></script>
    <script src="{{ asset('js/components/Footer4-20231219.js') }}"></script>
    
    <script src="{{ asset('js/components/ViewAllMatrix-20240123.js') }}"></script>

    <script src="{{ asset('js/components/Diginet-20240307.js') }}"></script>

    <script src="{{ asset('js/components/SearchModal-20240320.js') }}"></script>
    <script src="{{ asset('js/components/KeyArrowTable.js') }}"></script>
    <script src="{{ asset('js/applayout.js') }}"></script>
    <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
    <script src="{{ asset('js/signature_pad@4.0.0.umd.min.js') }}"></script>
    <script src="{{ asset('js/qrcode@1.0.0.min.js') }}"></script>

    <script src="{{ asset('js/go@2.3.8.js') }}"></script>
    <script src="{{ asset('js/html2pdf@0.10.1.bundle.min.js') }}"></script>
    
    <script src="{{ asset('js/fullcalendar/core@6.1.8.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar/interaction@6.1.8.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar/daygrid@6.1.8.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar/timegrid@6.1.8.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar/list@6.1.8.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar/multimonth@6.1.8.min.js') }}"></script>
    
    <script src="{{ asset('js/tinymce@6.7.3.min.js') }}" referrerpolicy="origin"></script>
    
    <script src="{{ asset('js/justifiedGallery@3.8.1.min.js') }}"></script>
    <script src="{{ asset('js/lightgallery.js') }}"></script>
    <script src="{{ asset('js/jsdiff.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/10.0.1/decoupled-document/ckeditor.js"></script>
</head>