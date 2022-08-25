<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>TLC</title>
  <link rel="icon" href="{{asset('logo/tlc.svg')}}" type="image/icon type">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('AdminLTE/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('AdminLTE/dist/css/adminlte.min.css')}}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{asset('AdminLTE/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="{{asset('AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{asset('AdminLTE/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{asset('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('AdminLTE/plugins/select2/css/select2.min.css')}}">

  <link rel="stylesheet" href="{{asset('AdminLTE/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="{{asset('AdminLTE/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
  <!-- Theme style -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
  <link rel="stylesheet" href="{{asset('css/tlc.css')}}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to to the body tag
to get the desired effect
|---------------------------------------------------------|
|LAYOUT OPTIONS | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<body class="hold-transition sidebar-mini sidebar-collapse">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="/" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="{{url('https://www.tlcmodular.com/')}}" class="nav-link">Contact</a>
        </li>
      </ul>

      <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->

        <!-- Notifications Dropdown Menu -->

        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i class="fas fa-th-large"></i></a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="#" class="brand-link">
        <img src="{{asset('logo/tlc.svg')}}" alt="AdminLTE Logo" class="brand-image">
        <span class="brand-text font-weight-light"></span>
      </a>
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="https://wp.tlcmodular.com/wp-content/uploads/2022/07/bfdc18a057769428cd67-150x150.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="https://www.linkedin.com/in/ngo-dinh-canh-588839220/" class="d-block">Ngo Dinh Canh</a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul id="listMenus" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            @php
            $sideBar = json_decode(file_get_contents(storage_path() . "/json/configs/view/dashboard/sidebarProps.json"),true);
            @endphp
            @foreach ($sideBar as $group)
            <li class="nav-item has-treeview menu-close">
              <a href="#" class="nav-link">
                <i class="{{$group['icon']}}"></i>
                <p>
                  {{$group['title']}}
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                @foreach ($group['items'] as $item)
                <li class="nav-item">
                  <a href="{{url($item['href'])}}" class="nav-link">
                    <i class="{{$item['icon']}}"></i>
                    <p>{{$item['title']}}</p>
                  </a>
                </li>
                @endforeach
              </ul>
            </li>
            @endforeach


          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
    <!-- Main content -->
    @yield('content')
    <!-- /.content -->
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <strong>Copyright &copy; 2022-2023 <a href="https://www.tlcmodular.com/">tlcmodular.com</a>.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
      </div>
    </footer>
  </div>
  <!-- ./wrapper -->
  <!-- REQUIRED SCRIPTS -->
  <!-- jQuery -->
  <script src="{{asset('AdminLTE/plugins/jquery/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- AdminLTE -->
  <script src="{{asset('AdminLTE/dist/js/adminlte.js')}}"></script>
  <!-- OPTIONAL SCRIPTS -->
  {{-- <script src="{{asset('AdminLTE/plugins/chart.js/Chart.min.js')}}"></script> --}}

  <script src="{{asset('AdminLTE/dist/js/demo.js')}}"></script>

  {{-- <script src="{{asset('/AdminLTE/dist/js/pages/dashboard3.js')}}"></script> --}}
  <!-- AdminLTE for demo purposes -->
  <script src="{{asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js')}}"></script>

  <script src="{{asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

  <script src="{{asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>

  <script src="{{asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
  <!-- AdminLTE App -->
  <script src="{{asset('AdminLTE/dist/js/adminlte.min.js')}}"></script>
  <!-- Select2 -->
  <script src="{{asset('AdminLTE/plugins/select2/js/select2.full.min.js')}}"></script>
  <!-- Bootstrap4 Duallistbox -->
  <script src="{{asset('AdminLTE/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>
  <!-- InputMask -->
  <script src="{{asset('AdminLTE/plugins/moment/moment.min.js')}}"></script>

  <script src="{{asset('AdminLTE/plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
  <!-- date-range-picker -->
  <script src="{{asset('AdminLTE/plugins/daterangepicker/daterangepicker.js')}}"></script>
  <!-- bootstrap color picker -->
  <script src="{{asset('AdminLTE/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{asset('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
  <!-- Bootstrap Switch -->
  <script src="{{asset('AdminLTE/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
  {{-- Toastr --}}
  <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>

  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  {!! Toastr::message() !!}
  <script type="" src="{{asset('js/tlc.js')}}"></script>
</body>

</html>