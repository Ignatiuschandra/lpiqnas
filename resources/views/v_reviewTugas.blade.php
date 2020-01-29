@extends('layouts.master')
@section('title_nav', 'LPIQNAS | Review Tugas')
@section('judul_halaman', 'Review Tugas')

@section('konten')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-12">
      
      <div class="card">

        <div class="card-body">

          <table class="table" id="tabel-tugas">
            <thead>
              <tr>
                <th>Judul Tugas</th>
                <th>Pembuat</th>
                <th>Kelas</th>
                <th class="text-center">Aksi</th>
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
  var tableTugas;

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $(document).ready( function () {
    tableTugas = $('#tabel-tugas').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ url('review-tugas/get-json-tugas') }}",
      columns: [
          { data: 'tugas_judul' },
          { data: 'admin_nama_lengkap' },
          { data: null,
            render: function(data, type, row){
              return data.kelas_tingkat+' '+data.kelas_nama+' - '+data.kelas_tahun_ajaran;
          }},
          { data: null,
            render: function(data, type, row){
              return `<div class="btn-group btn-group-sm">
              <a href="{{ url('review-tugas/detail-tugas/`+data.tugas_id+`') }}" class="btn btn-primary" title="Detail"><i class="fas fa-eye"></i></a>
            </div>`;
            }
          }
      ],
      columnDefs: [{
        targets: 3,
        className: 'text-center'
      }]
    });
  });

  $('#tambahDataTugas').on('click', function(){
    $.ajax({
      url:"{{ url('tugas-management/tambah-tugas') }}",
      method:"POST", 
      data:$('#formTambah').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-tambah').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menambahkan Data Tugas.'
          });
          tableTugas.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menambahkan Data Tugas. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menambahkan Data Tugas. Hubungi Developer!'
        });
      }
    });
  });

  function setIdHapus(id){
    $('#tugas_id').val(id);
  }

  $('#hapusDataTugas').on('click', function(){
    $.ajax({
      url:"{{ url('tugas-management/hapus-tugas') }}",
      method:"POST", 
      data:{tugas_id : $('#tugas_id').val()},
      success:function(response) {
        if (response.success == true) {
          $('#modal-hapus').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Menghapus Data Tugas.'
          });
          tableTugas.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Menghapus Data Tugas. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Menghapus Data Tugas. Hubungi Developer!'
        });
      }
    });
  });

  function getDataVideo(id){
    $.ajax({
      url:"{{ url('video-management/get-video') }}",
      method:"POST", 
      data:{video_id : id},
      success:function(response) {
        $('#editJudulVideo').val(response.data.vb_judul);
        $('#editLink').val(response.data.vb_link);
        $('#editId').val(response.data.vb_id);
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Mengambil Data Video. Hubungi Developer!'
        });
      }
    });
  }

  $('#updateDataVideo').on('click', function(){
    $.ajax({
      url:"{{ url('video-management/update-video') }}",
      method:"POST", 
      data:$('#formEdit').serialize(),
      success:function(response) {
        if (response.success == true) {
          $('#modal-edit').modal('toggle');
          Toast.fire({
            type: 'success',
            title: 'Anda Berhasil Memperbaharui Data Video.'
          });
          tableTugas.ajax.reload();
        }else{
          Toast.fire({
            type: 'error',
            title: 'Anda Gagal Memperbaharui Data Video. Mohon Coba Kembali!'
          });  
        }
      },
      error:function(){
        Toast.fire({
          type: 'error',
          title: 'Anda Gagal Memperbaharui Data Video. Hubungi Developer!'
        });
      }
    });
  });
</script>
@endsection