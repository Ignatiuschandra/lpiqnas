<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title_nav')</title>

  <!-- Favicons -->
  <link href="{{ asset('assets/dist/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/dist/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- IonIcons -->
  <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Timepicker -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/timepicker/css/bootstrap-material-datetimepicker.css') }}">

  <style type="text/css">
    .nav-active{
      background-color: rgba(255,255,255,.1);
    }
  </style>
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
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-teal navbar-light">

    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars" style="color: #ffffff;"></i></a>
      </li>
    </ul>
      
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user" style="color: #ffffff;">&emsp;{{\Illuminate\Support\Facades\Auth::user()->admin_nama_lengkap}}</i>
          <span class="badge badge-warning navbar-badge"></span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt" style="color: #ffffff"></i>
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
      </li>

    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #056644 !important; ">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img src="{{ asset('assets/dist/img/logo_lpiqnas.png') }}" alt="AdminLTE Logo" class="brand-image">
      <span class="brand-text font-weight-light" style="color: #ffffff;">Adm LPIQNAS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block" style="color:#ffffff;">ADMIN</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <a href="{{url('user-management')}}" class="nav-link {{ session('navbar') == 'user' ? 'nav-active' : '' }}" style="color: #ffffff;">
              <i class="nav-icon fas fa-users"></i>
              <p>
                User Management
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('materi-management')}}" class="nav-link {{ session('navbar') == 'materi' ? 'nav-active' : '' }}" style="color: #ffffff;">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Materi Management
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('video-management')}}" class="nav-link {{ session('navbar') == 'video' ? 'nav-active' : '' }}" style="color: #ffffff;">
              <i class="nav-icon fas fa-play"></i>
              <p>
                Video Management
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('tugas-management')}}" class="nav-link {{ session('navbar') == 'tugas' ? 'nav-active' : '' }}" style="color: #ffffff;">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                Tugas Management
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('ujian-management')}}" class="nav-link {{ session('navbar') == 'ujian' ? 'nav-active' : '' }}" style="color: #ffffff;">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Ujian Management
              </p>
            </a>
          </li>
          
          <li class="nav-item has-treeview {{ session('navbar') == 'rtugas' || session('navbar') == 'rujian' ? 'menu-open' : '' }}">
            <a href="#" class="nav-link" style="color: #ffffff;">
              <i class=" nav-icon far fa-file-pdf"></i>
              <p>
                Review
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" {{ session('navbar') == 'rtugas' || session('navbar') == 'rujian' ? 'style=display:block' : '' }}>
              <li class="nav-item">
                <a href="{{url('review-tugas')}}" class="nav-link {{ session('navbar') == 'rtugas' ? 'nav-active' : '' }}" style="color: #ffffff;">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tugas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('review-ujian')}}" class="nav-link {{ session('navbar') == 'rujian' ? 'nav-active' : '' }}" style="color: #ffffff;">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Ujian</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="{{url('jadwal')}}" class="nav-link {{ session('navbar') == 'jadwal' ? 'nav-active' : '' }}" style="color: #ffffff;">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Jadwal Kelas
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('pengumuman')}}" class="nav-link {{ session('navbar') == 'info' ? 'nav-active' : '' }}" style="color: #ffffff;">
              <i class="nav-icon fas fa-info"></i>
              <p>
                Informasi dan Event
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{url('diskusi')}}" class="nav-link {{ session('navbar') == 'diskusi' ? 'nav-active' : '' }}" style="color: #ffffff;"> 
              <i class="nav-icon fas fa-plus-circle"></i>
              <p>
                Posting dan Diskusi
              </p>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a href="pages/examples/komentar.html" class="nav-link" style="color: #ffffff;">
              <i class="nav-icon fas fa-comments"></i>
              <p>
                Komentar
              </p>
            </a>
          </li> -->

          <li class="nav-item">
            <a href="{{url('broadcast-wa')}}" class="nav-link {{ session('navbar') == 'wa' ? 'nav-active' : '' }}" style="color: #ffffff;">
              <i class="nav-icon fab fa-whatsapp"></i>
              <p>
                Broadcast
              </p>
            </a>
          </li>

      </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"> @yield('judul_halaman')</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
              <li class="breadcrumb-item active"> @yield('judul_halaman')</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    @yield('konten')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer"> 
    Copyright &copy; 2020 <a href="http://adminlte.io">AdminLTE.io</a>
    || All rights reserved || Designed by <a href="http://carryu.id">CarryU</a>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<!-- AdminLTE App -->
<!-- <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script> -->
<!-- Moment -->
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<!-- Timepicker -->
<script src="{{ asset('assets/plugins/timepicker/js/bootstrap-material-datetimepicker.js') }}"></script>

<!-- OPTIONAL SCRIPTS -->
<!-- <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script> -->
<!-- <script src="{{ asset('assets/dist/js/demo.js') }}"></script> -->
<!-- <script src="{{ asset('assets/dist/js/pages/dashboard3.js') }}"></script> -->
@yield('scriptTambahan')
</body>
</html>