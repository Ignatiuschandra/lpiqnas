@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Review Ujian')
@section('judul_halaman', 'Review Ujian')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">
          <table class="table text-center" id="tabel-ujian">
            <thead>
              <tr>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Nilai</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card-->

   </div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
  <!-- /.container-fluid -->
@endsection

@section('scriptTambahan')
<!-- page script -->
<script>
  var tableUjian;

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $(document).ready( function () {
    tableUjian = $('#tabel-ujian').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ url("review-ujian/get-json-ujian-detail/$id") }}',
      columns: [
          { data: 'siswa_nama_lengkap' },
          { data: null,
            render: function(data, type, row){
              return data.kelas_tingkat+' '+data.kelas_nama+' - '+data.kelas_tahun_ajaran;
          }},
          { data: 'sn_nilai' }
      ]
    });
  });
</script>
@endsection