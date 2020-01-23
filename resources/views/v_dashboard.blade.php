@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Home')
@section('judul_halaman', 'Home')

@section('konten')
<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <img src="{{ asset('assets/dist/img/logo_lpiqnas.png') }}" class="center" style="display: block; margin-left: auto; margin-right: auto; width: 30%;">
            <p style="text-align: center; font-size: 50px; color: #056644; ">Selamat Datang di Aplikasi LPIQNAS</p>
          </div>
        </div>
      </div>
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
  <!-- /.container-fluid -->

@endsection